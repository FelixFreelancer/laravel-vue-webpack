<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shipment\ListRequest;
use App\Http\Requests\Admin\Shipment\StoreRequest;
use App\Http\Requests\Admin\Shipment\UpdateRequest;
use App\Http\Requests\Admin\Shipment\UpdateStatusRequest;
use App\Http\Requests\Admin\Shipment\RevertStatusRequest;
use App\Models\User;
use App\Transformers\Admin\PhotoRequestTransformer;
use App\Transformers\Admin\InvoiceTransformer;
use App\Transformers\Admin\DashboardShipmentTransformer;
use App\Transformers\Admin\DashboardQuotationTransformer;
use App\Models\ShipmentHistory;
use App\Models\Invoice;
use App\Models\PhotoRequest;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Quotation;
use App\Models\Media;
use App\Notifications\ShipmentCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Library\Api;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentShipped;

class ShipmentController extends Controller
{

    public function index(ListRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Shipment Listing','<b>'.$name."</b> has viewed <b>Shipment Tab</b>.");
        $shipment = Shipment::search(request()->all());
        foreach($shipment['shipments'] as $key => $value){
          $value->status_value = ($value->status  != '') ? config('site.shipment_statuses.' . $value->status) : '';
          $value->is_payment_done = ($value->status  == '3') ? true : false;
          $value->is_ready_for_shipment = ($value->status  == '4') ? true : false;
          $value->is_delivered = ($value->status  == '5') ? true : false;
          $value->parcel_display_desc = substr($value->parcel_desc,0,100);
          $value->parcel_sub_desc = substr($value->parcel_desc,0,100);
        }
        $data['data'] = $shipment;
        $data['data']['shipment_status'] = config('site.shipment_statuses');
        return Api::ApiResponse($data);
    }


    public function getShipment()
    {
        $shipment = Shipment::getOldShipments(request()->all());
        $shipmentObj = [];
        foreach($shipment as $key => $value){
          $shipmentObj[] = DashboardShipmentTransformer::transform($value);
        }
        $data['data']['shipments'] = $shipmentObj;

        $quotation = Quotation::getOldQuotations(request()->all());
        $quotationObj = [];
        foreach($quotation as $key => $value){
          $quotationObj[] = DashboardQuotationTransformer::transform($value);
        }
        $data['data']['quotations'] = $quotationObj;

		$invoice = Invoice::search(request()->all());
		$invoiceObj=[];
        foreach($invoice['invoices'] as $key => $value){
          if($value->entity_type == "1"){
            $value->entity = Shipment::where('id',$value->entity_id)->first();
          }
          $invoiceObj[] = InvoiceTransformer::transform($value);
        }
		$data['data']['invoices'] = $invoiceObj;

		$photo = PhotoRequest::search(request()->all());
		$photoObj=[];
        foreach($photo['photo_requests'] as $key => $value){
          $photoObj[] = PhotoRequestTransformer::transform($value);
        }
		$data['data']['photo_requests'] = $photoObj;

        return Api::ApiResponse($data);
    }

    public function store(StoreRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        $inputs = $request->all();
        $inputs['received_on'] = date_time_database($inputs['received_on']);
        $inputs['status'] = 1;
		$inputs['custom_shipping_price'] = $inputs['shipping_option_available'] == 'false' ? $inputs['custom_shipping_price'] :NULL;
        $shipment = new Shipment($inputs);

        if (!$shipment->save()) {
            $data['data']['error'] = 'Something went wrong.';
            $statusCode = 422;
            return Api::ApiResponse($data,$statusCode);
        }

        if ($request['image']) {
          foreach($request['image'] as $key => $value){
            $rst = fileUpload($value, 'shipment');
            $rst['main_id'] = $shipment->id ;
            $media = Media::create($rst);
          }
        }
        $user = User::find($request['user_id']);
        addShipmentStatus($shipment->id, 1,$loggedInUser['user']['id'] );
        Notification::send($user, new ShipmentCreated($shipment,$user,  $loggedInUser['user']));
        addLog($loggedInUser['user']['id'],'Shipment Create','A new shipment <b>['.$shipment->id.']</b> has been added by <b>'.$name.'</b>');
        $data['data'] = $shipment->toArray();
        return Api::ApiResponse($data);
    }

    public function show($shipment)
    {
        $data = [];
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        $obj = Shipment::leftJoin('users','users.id','=','shipments.user_id')->select('shipments.*',DB::raw('concat(first_name," ",last_name) as user_name'))->where('shipments.id',$shipment)->first();
        if(!$obj){
          $data['data']['error'] = 'Shipment Not Found.';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $obj->received_on = datepicker_date_time($obj->received_on);
        $medias = Media::where('main_id', '=', $obj->id)
            ->where('type', '=', 'Shipment')
            ->get();
        $media = [];
        foreach($medias as $key => $value){
            $media[] = [
              'id' => $value->id,
              'image' => config('app.url').'/'.$value->media_path.$value->media_name,
            ];
        }
        addLog($loggedInUser['user']['id'],'Shipment View','A shipment <b>['.$shipment.']</b> has been viewed by <b>'.$name.'</b>');
        $obj->medias = $media;
        $data['data'] = $obj->toArray();
          return Api::ApiResponse($data);
    }

    public function getProgress($id)
    {
        $data['data'] = [];
        $shipment = Shipment::leftJoin('users','users.id','=','shipments.user_id')
            ->select('shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
            ->where('shipments.id',$id)
            ->first();
        $statusBy = ShipmentHistory::leftJoin('users','users.id','=','shipment_histories.user_id')
            ->where('shipment_histories.shipment_id',$id)
            ->select('shipment_histories.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
            ->get()
            ->keyBy('status_id');
        if(!$shipment){
          $data['data']['error'] = 'Shipment Not Found.';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $payment = Payment::where('entity_id',$id)->first();
        $step = [
          'user_id' => $shipment['user_id'],
          'user_name' => $shipment['user_name'],
        ];
        $step1['shipping_in'] = [
          'id' => $shipment['id'],
          'name' => $shipment['name'],
          'parcel_number' => $shipment['parcel_number'],
          'parcel_desc' => $shipment['parcel_desc'],
          'status_by' => isset($statusBy[1]) ? $statusBy[1]['user_id'] : NULL,
          'status_by_name' => isset($statusBy[1]) ? $statusBy[1]['user_name'] : NULL,
        ];
        $step2['shipping_select'] = [
          'shipping_out_amount' => $shipment['shipping_out_amount'],
          'shipping_out_service' => $shipment['shipping_out_service'],
          'shipping_out_company' => $shipment['shipping_out_company'],
        ];
        $step3['payment'] = [
          'transaction_id' => $payment['transaction_id'],
          'merchant_account_id' => $payment['merchant_account_id'],
          'seller_account_id' => $payment['seller_account_id'],
          'payment_gateway_status' => $payment['payment_gateway_status'],
          'payment_gateway_type' => config('site.payment_gateway_types.'.$payment['payment_gateway_type']) ? config('site.payment_gateway_types.'.$payment['payment_gateway_type']) : NULL ,
        ];
        $step4['payment_verified'] = [
          'status_by' => isset($statusBy[5]) ? $statusBy[5]['user_id'] : NULL,
          'status_by_name' => isset($statusBy[5]) ? $statusBy[5]['user_name'] : NULL,
        ];
        $step5['shipped'] = [
          'shipping_out_tracking' => $shipment['shipping_out_tracking'],
          'shipping_tracking_link' => $shipment['shipping_tracking_link'],
          'shipping_out_at' => $shipment['shipping_out_at'],
          'status_by' => isset($statusBy[6]) ? $statusBy[6]['user_id'] : NULL,
          'status_by_name' => isset($statusBy[6]) ? $statusBy[6]['user_name'] : NULL,
        ];
        $step6['delivered'] = [
          'delivered_at' => $shipment['delivered_at'],
          'status_by' => isset($statusBy[7]) ? $statusBy[7]['user_id'] : NULL,
          'status_by_name' => isset($statusBy[7]) ? $statusBy[7]['user_name'] : NULL,
        ];
        $data['data']['user'] = $step;
        switch ($shipment['status']) {
            case '1':
                $data['data'] = $step1;
                break;
            case '2':
                $data['data'] = array_merge($step1,$step2);
                break;
            case '3':
                $data['data'] = array_merge($step1,$step2,$step3);
                break;
            case '4':
                $data['data'] = array_merge($step1,$step2,$step3,$step4);
                break;
            case '5':
                $data['data'] = array_merge($step1,$step2,$step3,$step4,$step5);
                break;
            case '6':
                $data['data'] = array_merge($step1,$step2,$step3,$step4,$step5,$step6);
                break;
            default:
              break;
        }
          return Api::ApiResponse($data);
    }

    public function update($id,UpdateRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        $inputs = $request->all();
        $shipment = Shipment::getShipment($id);
        if(!$shipment){
          $data['data']['error'] = 'Shipment Not Found.';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $inputs['received_on'] = date_time_database($inputs['received_on']);
		$inputs['custom_shipping_price'] = $inputs['shipping_option_available'] == 'false' ? $inputs['custom_shipping_price'] :NULL;
        if (!$shipment->update($inputs)) {
          $data['data']['error'] = 'Something went wrong.';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }

        if ($request['image']) {
          foreach($request['image'] as $key => $value){
            $rst = fileUpload($value, 'shipment');
            $rst['main_id'] = $shipment->id ;
            $media = Media::create($rst);
          }
        }
        $medias = Media::where('main_id', '=', $shipment->id)->where('type', '=', 'Shipment')->get();
        $media = [];
        foreach($medias as $key => $value){
          $media[] = [
            'id' => $value->id,
            'image' => config('app.url').'/'.$value->media_path.$value->media_name
          ];
        }

        addLog($loggedInUser['user']['id'],'Shipment Update','A shipment <b>['.$id.']</b> has been updated by <b>'.$name.'</b>');
        $shipment->medias = $media;
        $data['data'] = $shipment->toArray();
        return Api::ApiResponse($data);
    }

    public function delete($id)
    {
        $statusCode = 200;
        $data['data'] = [];
        $shipment = Shipment::find($id);
        if($shipment){
			ShipmentItem::where('shipment_id',$id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
			Invoice::where('entity_id',$id)->where('entity_type',1)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
			Payment::where('entity_id',$id)->where('payment_type',1)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

          $shipment->delete();
		  $loggedInUser = Api::getAuthenticatedUser();
          $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
          addLog($loggedInUser['user']['id'],'Shipment Delete','A shipment <b>['.$id.']</b> has been deleted by <b>'.$name.'</b>');
        }else{
          $statusCode = 422;
          $data['data']['error'] = "Shipment Not Found";
        }
        return Api::ApiResponse($data, $statusCode);
    }

    public function updateStatus($id,UpdateStatusRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
		    $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        $shipment = Shipment::find($id);
        $user = User::find($shipment->user_id);
        switch ($request['status']) {
            case '4':
                $shipment->status = $request['status'];
				addLog($loggedInUser['user']['id'],'Shipment Payment Verified','A payment of shipment <b>['.$id.']</b> has been verified by <b>'.$name.'</b>');
                break;
            case '5':
                $shipment->shipping_out_tracking = $request['tracking_no'];
                $shipment->shipping_tracking_link = $request['tracking_link'];
                $shipment->shipping_out_at = date('Y-m-d H:i:s');
                $shipment->status = $request['status'];

                Mail::to($user->email)->send(new ShipmentShipped($user,$request['tracking_no'],$request['tracking_link']));
					addLog($loggedInUser['user']['id'],'Shipment Shipped','A shipment <b>['.$id.']</b> has been shipped by <b>'.$name.'</b>');
                break;
            case '6':
				$date = isset($request['delivered_at']) ? $request['delivered_at'] : date('Y-m-d H:i:s');
                $shipment->delivered_at = $date;
                $shipment->status = $request['status'];
					addLog($loggedInUser['user']['id'],'Shipment Delivered','A shipment <b>['.$id.']</b> has been delivered by <b>'.$name.'</b>');
                break;
            default:
              break;
        }
        $shipment->save();
        addShipmentStatus($id, $shipment->status,$loggedInUser['user']['id'] );
        $data['data'] = $shipment->toArray();
        return Api::ApiResponse($data);
    }

    public function revertStatus($id,RevertStatusRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $shipment = Shipment::getShipment($id);
        deleteShipmentStatus($id, $request['status'],$loggedInUser['user']['id'] );
        addShipmentStatus($id, $request['updated_status'],$loggedInUser['user']['id'] );
        $shipment->status = $request['updated_status'];
        $shipment->save();
        $data['data'] =  $shipment->toArray();
        return Api::ApiResponse($data);
    }

    public function getInsurance()
    {
      $input=request()->all();
      $searchQuery = isset($input['query']) ? $input['query'] : [];
      $shipment=Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.id as shipment_id','shipments.name as shipment_name','users.customer_code',
                                 'shipments.insurance_charge',DB::raw('concat(users.first_name," ",users.last_name) as user_name'),'shipments.user_id',
                                 'shipments.created_at'
                                )
                        ->leftjoin('users',function($query){
                          $query->on('users.id','=','shipments.user_id')
                            ->where('shipments.insurance_charge','!=','0');
                        })
                        ->whereNotNull('shipments.insurance_charge')
                        ->where('shipments.insurance_charge','!=','0');

                        if (isset($searchQuery['shipment_name']))  {
                                 $shipment->where('shipments.name', 'like', '%' . $searchQuery['shipment_name'] . '%');
                        }
                        if (isset($searchQuery['user_name']))  {
                             $shipment->where('users.first_name', 'like', '%' . $searchQuery['user_name'] . '%')
                                      ->orwhere('users.last_name', 'like', '%' . $searchQuery['user_name'] . '%');
                        }
                if(isset($input['page'])){
                  $start = $input['limit'] * ($input['page'] - 1);
                  $end = $input['limit'];
                  $shipment->skip($start)->take($end);
                }
      $shipment=$shipment->orderBy('shipment_id','desc')->get();
      $data['data']['insurance'] =  $shipment->toArray();
      $data['data']['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return Api::ApiResponse($data);
    }
}
