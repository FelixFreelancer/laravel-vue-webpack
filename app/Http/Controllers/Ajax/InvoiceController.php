<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Shipment;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Transformers\InvoiceTransformer;
use App\Transformers\InvoiceShipmentEntityTransformer;
use App\Transformers\InvoiceShopperEntityTransformer;
use App\Library\Api;
use DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pagination = config("site.pagination");
        $data = [];
		$page = request('page') ? request('page') : 1;
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $invoice = Invoice::select(DB::raw('SQL_CALC_FOUND_ROWS invoices.id'),'invoices.*')
                ->where('user_id',auth()->id())
                ->skip($start)
                ->take($count)
                ->orderBy('id','desc')
                ->get();

        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $shipmentId = $invoice->where('entity_type',1)->pluck('entity_id');
        $personalId = $invoice->where('entity_type',2)->pluck('entity_id');
        $shipment = Shipment::leftJoin('shipment_histories',function($join){
                    $join->on('shipment_histories.shipment_id','=','shipments.id')
                      ->where('shipment_histories.status_id','2');
                    })
                    ->leftJoin('users','users.id','=','shipment_histories.user_id')
                    ->select('shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
                    ->whereIn('shipments.id',$shipmentId)
                    ->get();

        $quotation = QuotationItem::leftJoin('quotations','quotations.id','=','quotation_items.quotation_id')
              ->select('quotation_items.*','quotations.quotation_number','quotations.total')
              ->whereIn('quotation_id', $personalId)
              ->get();

        $invoices = $invoice->map(function ($item, $key) use ($shipment, $quotation) {
            if($item->entity_type == "1"){
              $item->entity = InvoiceShipmentEntityTransformer::transform($shipment->where('id',$item->entity_id)->first());
		  }else if($item->entity_type == "2"){
              $obj = [];
              $rst = $quotation->where('quotation_id',$item->entity_id);
              foreach($rst as $k => $val){
                $obj[] = InvoiceShopperEntityTransformer::transform($val);
              }
              $item->entity = $obj;
            }
            return $item;
        });

        $invoiceObj = [];
        foreach($invoices as $key => $value){
          $invoiceObj[] = InvoiceTransformer::transform($value);
        }

        $data['data']['invoice'] = $invoiceObj;
        $data['data']['page'] = $page+1;
        $data['data']['pagination'] = getPaginationObject($total,$start,$count);
        return Api::ApiResponse($data);
    }

}
