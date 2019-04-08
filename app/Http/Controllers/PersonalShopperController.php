<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use App\Notifications\QuotationCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PersonalShopperController extends Controller
{
    public function index()
    {
        $data = [];

        $data['quotations'] = Quotation::where('user_id', '=', auth()->user()->id)->get();

        $ids = $data['quotations']->pluck('id');

        $quotation_items = QuotationItem::whereIn('quotation_id', $ids)->get();

        $data['quotations'] = $data['quotations']->map(function ($item, $key) use ($quotation_items) {
            $item->items = $quotation_items->where('quotation_id', '=', $item->id);

            return $item;
        });

        $data['currencies'] = Currency::pluck('symbol', 'symbol');

        return view('frontend.pages.users.shopper', $data);
    }

    public function store()
    {
        $this->validate(request(), Quotation::validationRules(), Quotation::validationMessages());

        $todays_quatations = Quotation::where(DB::raw('month(created_at)'), '=', date('m'))
            ->where(DB::raw('year(created_at)'), '=', date('Y'))
            ->count();

        $quotation_number = 'G' . date('ym') . sprintf('%03s', ($todays_quatations + 1));

        $quotation = Quotation::create([
            'user_id'          => auth()->user()->id,
            'quotation_number' => $quotation_number,
            'status'           => 1
        ]);

        foreach (request('store_name') as $key => $value) {
            $inputs = [
                'quotation_id'        => $quotation->id,
                'store_name'          => $value,
                'direct_link'         => request('direct_link')[$key],
                'item_name'           => request('item_name')[$key],
                'user_price_currency' => request('user_price_currency')[$key],
                'user_price'          => request('user_price')[$key],
                'color'               => request('color')[$key],
                'quantity'            => request('quantity')[$key],
                'status'              => 1
            ];
            QuotationItem::create($inputs);
        }
        $admins = User::role(['Admin','Super Admin'])->get();
        $user_name = ucwords(strtolower(auth()->user()->first_name . ' ' . auth()->user()->last_name));
        Notification::send($admins, new QuotationCreated($quotation_number, $user_name));

        $loggedInUser = auth()->user();
        $name = $loggedInUser['first_name']." ".$loggedInUser['last_name'];
        addLog($loggedInUser['id'],'Quotation Update',"A Quotation <b>[".$quotation_number."]</b> has been added by <b>".$name.'</b>');

        session()->flash('message', 'Quotation added successfully.');
        session()->flash('class', 'success');
        return back();
    }


    public function payment(Quotation $quotation)
    {
        $data = [];

		$data['id'] = $quotation['id'];
		$data['user'] = auth()->id();
		$data['payment_type'] = '2';

        return view('frontend.pages.users.general-payment', $data);
    }
}
