<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Shipment\PersonalShopperRequest;
use App\Transformers\QuotationTransformer;
use App\Models\Currency;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use App\Notifications\QuotationCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Library\Api;

class PersonalShopperController extends Controller
{
    public function index()
    {
        $data = [];
        $user = auth()->user();

        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $quotations = Quotation::select(DB::raw('SQL_CALC_FOUND_ROWS quotations.id'),'quotations.*')
                  ->where('user_id', '=', $user->id)
                  ->take($count)
                  ->skip($start)
                  ->get();
        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $ids = $quotations->pluck('id');

        $quotation_items = QuotationItem::whereIn('quotation_id', $ids)->get();

        $quotations = $quotations->map(function ($item, $key) use ($quotation_items) {
            $item->items = $quotation_items->where('quotation_id', '=', $item->id);
            return $item;
        });
        $quotationsObj = [];
        foreach($quotations as $key => $value){
          $quotationsObj[] = QuotationTransformer::transform($value);
        }
        $data['data']['quotations'] = $quotationsObj;
        $data['data']['currencies'] = Currency::pluck('symbol', 'symbol');
        return Api::ApiResponse($data);
    }

    public function store(PersonalShopperRequest $request)
    {
        $user = auth()->user();

        $todays_quatations = Quotation::where(DB::raw('month(created_at)'), '=', date('m'))
            ->where(DB::raw('year(created_at)'), '=', date('Y'))
            ->count();

        $quotation_number = 'G' . date('ym') . sprintf('%03s', ($todays_quatations + 1));

        $quotation = Quotation::create([
            'user_id'          => $user->id,
            'quotation_number' => $quotation_number,
            'status'           => 1
        ]);
        $quotationArray = $quotation->toArray();
        $quotationArray['items'] = [];

        foreach ($request['store_name'] as $key => $value) {
            $inputs = [
                'quotation_id'        => $quotation->id,
                'store_name'          => $value,
                'direct_link'         => $request['direct_link'][$key],
                'item_name'           => $request['item_name'][$key],
                'user_price_currency' => $request['user_price_currency'][$key],
                'user_price'          => $request['user_price'][$key],
                'color'               => $request['color'][$key],
                'quantity'            => $request['quantity'][$key],
                'status'              => 1
            ];
            $item = QuotationItem::create($inputs);
            $quotationArray['items'][] = $item;
        }
        $admins = User::role(['Admin','Super Admin'])->get();
        $user_name = ucwords(strtolower($user->first_name . ' ' . $user->last_name));
        Notification::send($admins, new QuotationCreated($quotation_number, $user));

        addLog($user['id'],'Quotation Update',"A Quotation <b>[".$quotation_number."]</b> has been added by <b>".$user_name.'</b>');

        $data['data'] =  QuotationTransformer::transform($quotationArray);
        return Api::ApiResponse($data);
    }

}
