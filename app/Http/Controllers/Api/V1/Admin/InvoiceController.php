<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Shipment;
use App\Models\Quotation;
use App\Transformers\Admin\InvoiceTransformer;
use App\Library\Api;
use DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoiceObj = [];
        $invoice = Invoice::search(request()->all());

        foreach($invoice['invoices'] as $key => $value){
          // $value['total'] = 0;
          // if($value['entity_type'] == "1"){
          //   $shipment = Shipment::find($value['entity_id']);
          //   $value['total'] = $shipment['total'];
          // }
          // if($value['entity_type'] == "2"){
          //   $quotation = Quotation::find($value['entity_id']);
          //   $value['total'] = $quotation['total'];
          //
          if($value->entity_type == "1"){
            $value->entity = Shipment::where('id',$value->entity_id)->first();
          }
          $invoiceObj[] = InvoiceTransformer::transform($value);
        }
		$loggedInUser = Api::getAuthenticatedUser();
  		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
  		addLog($loggedInUser['user']['id'],'Inquiry Listing','<b>'.$name.'</b> has viewed <b>Invoice Tab</b>.');
        $data['data']['invoice'] = $invoiceObj;
        $data['data']['count'] = $invoice['count'];
        return Api::ApiResponse($data);
    }

}
