<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use App\Notifications\QuotationReponseFromAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class QuotationController extends Controller
{
    public function index()
    {
        return view('admin.pages.quotations.index');
    }

    public function indexDatatable()
    {
        $data = Quotation::select('quotations.quotation_number', 'quotations.total as user_total_price'),
            'quotations.status', DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
            ->leftJoin('users', 'users.id', '=', 'quotations.user_id')
            ->groupBy('quotations.quotation_number', 'quotations.status', 'users.id');

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                if ($row->status != null) {
                    return config('site.quotation_status.' . $row->status);
                } else {
                    return 'Pending';
                }
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-primary" href="' . url()->route('admin.quotations.show', $row->quotation_number) . '"><i class="fa fa-eye"></i></a>';
            })
            ->make(true);
    }

    public function show($quotation_number)
    {
        $data = [];
        $data['quotation_number'] = $quotation_number;
        $quotation = Quotation::select('quotations.user_id', 'quotations.id', DB::raw('sum(quotation_items.user_price) as total'), 'quotations.status')
            ->where('quotation_number', '=', $quotation_number)
            ->leftJoin('quotation_items', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->groupBy('quotations.quotation_number', 'quotations.user_id', 'quotations.status', 'quotations.id')
            ->first();
        $data['quotation'] = Quotation::where('quotation_number', '=', $quotation_number)->get();
        $data['quotation_items'] = QuotationItem::where('quotation_id', '=', $quotation->id)->get();
        $data['user'] = User::find($quotation->user_id);
        $data['user_total'] = $quotation->user_price_currency . $quotation->total;
        $data['currencies'] = Currency::pluck('symbol', 'symbol');
        $data['status'] = $quotation->status;
        return view('admin.pages.quotations.show', $data);
    }

    public function update($quotation_number)
    {
        $this->validate(request(), Quotation::adminValidationRules(), Quotation::adminValidationMessages());
        $item_ids = array_keys(request('admin_price'));
        $items = QuotationItem::whereIn('id', $item_ids)->get();
        foreach ($items as $key => $value) {
            $value->update([
                'admin_price_currency' => request('admin_price_currency')[$value->id],
                'admin_price'          => request('admin_price')[$value->id],
                'status'               => '1'
            ]);
        }
        $quotation = Quotation::where('quotation_number', '=', $quotation_number)->first();
        $user = User::where('id', '=', $quotation->user_id)->get();
        Notification::send($user, new QuotationReponseFromAdmin($quotation_number, auth()->user()));
        session()->flash('message', 'Quotation updated successfully.');
        session()->flash('class', 'success');
        return back();
    }
}
