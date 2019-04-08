<?php

namespace App\Http\Controllers\Ajax;

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
		$page = request('page') ? request('page') : 1;
        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $quotes = Quotation::select(DB::raw('SQL_CALC_FOUND_ROWS quotations.id'),'quotations.*')
                  ->where('user_id', '=', $user->id);
        if(request('page')){
          $quotes->take($count)
            ->skip($start);
        }
        $quotations = $quotes->orderBy('id','desc')
                  ->get();
        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $ids = $quotations->pluck('id');

        $quotation_items = QuotationItem::whereIn('quotation_id', $ids)->get();
        // $quotations->total = 0;

        $quotations = $quotations->map(function ($item, $key) use ($quotation_items) {
            $item->items = $quotation_items->where('quotation_id', '=', $item->id);
          //  $item->total = 0;
            $item->is_admin_price_exist = false;
            foreach($item->items as $k => $val){
          //    $item->total += ($val->admin_price * $val->quantity);
              if($val->admin_price != ''){
                $item->is_admin_price_exist = true;
              }
            }
            return $item;
        });
        $quotationsObj = [];
        foreach($quotations as $key => $value){
          $quotationsObj[] = QuotationTransformer::transform($value);
        }
        $data['data']['quotations'] = $quotationsObj;
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);
        $data['data']['currencies'] = Currency::pluck('symbol', 'symbol');
		$msg = '';
		if(session()->has('payment')){
			$payment = session()->pull('payment');
			$msg = 'Your payment has been processed successfully';
		}
        return Api::ApiResponse($data,200,$msg);
    }

    public function store(PersonalShopperRequest $request)
    {
        $user = auth()->user();

        $todays_quatations = Quotation::where(DB::raw('month(created_at)'), '=', date('m'))
            ->where(DB::raw('year(created_at)'), '=', date('Y'))
			->withTrashed()
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
        Notification::send($admins, new QuotationCreated($quotation, $user));

        addLog($user['id'],'Quotation Update',"A Quotation <b>[".$quotation_number."]</b> has been added by <b>".$user_name.'</b>');

        $data['data'] =  QuotationTransformer::transform($quotationArray);
        return Api::ApiResponse($data,200,'Your request has been submitted successfully');
    }

}
