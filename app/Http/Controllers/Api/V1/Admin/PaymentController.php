<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\QuotationItem;
use App\Library\Api;
use DB;

class PaymentController extends Controller
{

    public function index()
    {
        $rst = Payment::search(request()->all());
        foreach($rst['payment'] as $key => $value){
          $entity = '';
          if($value->payment_type ==1){
            $entity = Shipment::find($value->entity_id);
          }
          if($value->payment_type == 2){
            $entity = QuotationItem::select('item_name as name')->where('quotation_id',$value->entity_id)->first();
          }
          $value->entity_name = (isset($entity) && $entity != '') ? $entity['name'] : NULL;
          $value->amount = appendCurrency($value->amount);
          $value->status = $value->status == 3 ? 'Success' : 'Pending';
          $value->payment_type = config('site.payment_types.'.$value->payment_type) ?  config('site.payment_types.'.$value->payment_type) : NULL;
          $value->created_date = strtotime($value->created_at);
          $value->payment_gateway_type = config('site.payment_gateway_types.'.$value->payment_gateway_type) ?  config('site.payment_gateway_types.'.$value->payment_gateway_type) : NULL;
          
          $value->payment_gateway_status = (isset($value->payment_gateway_status) && $value->payment_gateway_status != '') ? $value->payment_gateway_status : 'Pending';
        }
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Transaction Listing','<b>'.$name.'</b> has viewed the <b>Transaction Tab</b>.');

        foreach(config('site.payment_types') as $key => $value){
          $typeObj = [];
          $typeObj['key'] = $key;
          $typeObj['value'] = $value;
          $rst['payment_type'][] = $typeObj;
        }

        foreach(config('site.payment_gateway_types') as $key => $value){
          $typeObj = [];
          $typeObj['key'] = $key;
          $typeObj['value'] = $value;
          $rst['payment_gateway'][] = $typeObj;
        }
        $data['data'] = $rst;
        return Api::ApiResponse($data);
    }

}
