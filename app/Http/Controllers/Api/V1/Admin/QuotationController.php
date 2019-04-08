<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quotation\UpdateRequest;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\QuotationItem;
use App\Library\Api;
use Illuminate\Support\Facades\Notification;
use App\Notifications\QuotationReponseFromAdmin;
use App\Transformers\Admin\DashboardQuotationTransformer;
use DB;

class QuotationController extends Controller
{

    public function index()
    {
        $quotation = Quotation::search(request()->all());
        foreach($quotation['quotation'] as $key => $value){
          $value->status_value = ($value->status  != '') ? config('site.quotation_status.' . $value->status) : 'Pending';
          $value->requested_on = strtotime($value->created_at);
          $value->user_total_price = appendCurrency($value->user_total_price);
        }
        $data['data'] = $quotation;

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Quotation Listing','<b>'.$name."</b> has been viewed <b>Quotation Tab</b>.");

        $data['data']['quotation_status'] = config('site.quotation_status');
        return Api::ApiResponse($data);
    }

    public function show($quotationNumber)
    {
        $data['data'] = [];
        $quotationObj =  Quotation::where('quotation_number', '=', $quotationNumber)->get()->toArray();
        if(count($quotationObj) == 0){
          $data['data']['error'] = "Quotation Not Found";
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $quotation = Quotation::select('quotations.user_id', 'quotations.id', DB::raw('sum(quotation_items.user_price) as total'),
         'quotations.status')
          ->where('quotation_number', '=', $quotationNumber)
          ->leftJoin('quotation_items', 'quotation_items.quotation_id', '=', 'quotations.id')
          ->groupBy('quotations.quotation_number', 'quotations.user_id', 'quotations.status', 'quotations.id')
          ->first();
        $items = QuotationItem::where('quotation_id', '=', $quotation['id'])->get();
        foreach($items as $key => $value){
          $items[$key]['user_price'] = $value['user_price_currency'].$value['user_price'];
          $items[$key]['admin_price'] = $value['admin_price'];
        }
        $data['data'] = [
          'quotations' => $quotationObj,
          'quotation_items' => [
            'items' => $items,
            'count' => QuotationItem::where('quotation_id', '=', $quotation['id'])->count(),
          ],
          'user' => User::find($quotation['user_id']),
          'user_total' => appendCurrency($quotation['total']),
        //  'currencies' => Currency::pluck('symbol', 'symbol'),
          'status' =>  $quotation['status'] != '' ? config('site.quotation_status.' . $quotation['status']) : 'Pending',
          'is_editable' =>  $quotation['status'] != '3' ? true:false,
        ];

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Quotation View',"A Quotation <b>[".$quotationNumber."]</b> has been viewed by <b>".$name."</b>");

        return Api::ApiResponse($data);
    }

    public function update($quotation_number,UpdateRequest $request)
    {
        $data['data'] = [];
        $item_ids = array_keys($request['admin_price']);
        $items = QuotationItem::whereIn('id', $item_ids)->get();
        $total = 0;
        foreach ($items as $key => $value) {
            $value->update([
                'admin_price_currency' => config('site.default_currency'),
                'admin_price'          => $request['admin_price'][$value->id],
            ]);
            $total += ($request['admin_price'][$value->id] * $value->quantity);
        }
        $quotation = Quotation::where('quotation_number', '=', $quotation_number)->first();
        $quotation->total = $total;
        $quotation->status = 2;
        $quotation->status_by = auth()->id();
        $quotation->save();

        $user = User::where('id', '=', $quotation->user_id)->get();
        $loggedInUser = Api::getAuthenticatedUser();

        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Quotation Update',"A Quotation <b>[".$quotation_number."]</b> has been updated by <b>".$name."</b>");
       
        Notification::send($user, new QuotationReponseFromAdmin($quotation,$loggedInUser['user'],$user[0]));
        return Api::ApiResponse($data);
    }

    public function delete($id)
    {
		$statusCode = 200;
        $data['data'] = [];
        $quotation = Quotation::find($id);
        if($quotation){
			QuotationItem::where('quotation_id',$id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
			Invoice::where('entity_id',$id)->where('entity_type',2)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
			Payment::where('entity_id',$id)->where('payment_type',2)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

		  $loggedInUser = Api::getAuthenticatedUser();
		  $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		  addLog($loggedInUser['user']['id'],'Quotation Delete','A Quotation <b>['.$quotation['quotation_number'].']</b> has been deleted by <b>'.$name.'</b>');
		  $quotation->delete();
        }else{
          $statusCode = 422;
          $data['data']['error'] = "Quotation Not Found";
        }
        return Api::ApiResponse($data);
    }
}
