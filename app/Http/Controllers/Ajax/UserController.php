<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\User\UpdateRequest;
use App\Http\Requests\Front\Shipment\SelectShippingOptionRequest;
use App\Http\Requests\Front\User\UpdateProfilePicRequest;
use App\Http\Requests\Front\User\VerifyOtpRequest;
use App\Http\Requests\Front\User\Verify2FARequest;
use App\Http\Requests\Front\AutoRenewRequest;
use App\Transformers\ReadyForShippingTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\ActionBoxTransformer;
use App\Transformers\WarehouseTransformer;
use App\Transformers\ShipmentTransformer;
use App\Transformers\HistoryTransformer;
use App\Transformers\ProfileTransformer;
use App\Models\User;
use App\Models\Media;
use App\Models\PhotoRequest;
use App\Models\Otp;
use App\Models\UserMobile;
use App\Models\Invoice;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestShipmentItemPhoto;
use App\Library\Api;
use Hash;
use DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function paymentDetail()
    {
		$paymentStartObj = Payment::where('user_id',auth()->id())
					->where('payment_type',3)
					->where('status',3)
					->first();
		$paymentLastObj = Payment::where('user_id',auth()->id())
					->where('payment_type',3)
					->where('status',3)
					->orderBy('id','desc')
					->first();
		$data['data'] = [
			'first_payment' => [
				'id' => $paymentStartObj['id'],
				'started_at' => date('M d,Y',strtotime($paymentStartObj['created_at'])),
			],
			'last_payment' => [
				'id' => $paymentLastObj['id'],
				'payment_gateway_type' => $paymentLastObj['payment_gateway_type'],
				'payment_gateway_type_text' => config('site.payment_gateway_types.'.$paymentLastObj['payment_gateway_type']) ? config('site.payment_gateway_types.'.$paymentStartObj['payment_gateway_type']) : ''
			]
		];
        return Api::ApiResponse($data);
    }
    //
    // public function autorenew(AutoRenewRequest $request)
    // {
    //     $data['data'] = [];
    //     $user = auth()->user();
	// 	$msg = 'Please upgrade your plan for accessing this feature.';
	// 	if($user->plan_type == "paid"){
	// 		$payment = Payment::find($request['payment_id']);
	// 		if($payment->payment_gateway_type == 2){
    //
	// 		}
	// 		$msg = 'Auto renewal added successfully';
	// 		$user->update(['auto_renew'=>1]);
	// 	}
    //     return Api::ApiResponse($data,200,$msg);
    // }
    //
    // public function removeAutorenew(AutoRenewRequest $request)
    // {
    //     $data['data'] = [];
    //     $user = auth()->user();
	// 	$msg = 'Please upgrade your plan for accessing this feature.';
	// 	if($user->plan_type == "paid"){
	// 		$msg = 'Auto renewal removed successfully';
	// 		$user->update(['auto_renew'=>0]);
	// 	}
    //     return Api::ApiResponse($data,200,$msg);
    // }

    public function enableTwoFactor()
    {
		$google2fa = app('pragmarx.google2fa');
		$secret = $google2fa->generateSecretKey();
        // auth()->user()->update([
        //     'google2fa_secret' => $secret
        // ]);
		$data['data'] = [
			'qr' => $google2fa->getQRCodeInline(
				config('app.name'),
				auth()->user()->email,
				$secret
			),
			'secret' => $secret,
		];
        return Api::ApiResponse($data,200);
    }

    public function enableTwoFactorWithVerification(Verify2FARequest $request)
    {

        $google2fa = app('pragmarx.google2fa');
		$data['data'] = [];
        $verification = $google2fa->verifyGoogle2FA($request->secret, $request->code);

        if ($verification) {
			$statusCode = 200;
			auth()->user()->update([
	            'google2fa_secret' => $request->secret
	        ]);
			$input = [
				'user_id' => auth()->id(),
				'mail' => 'The Two Factor Authentication of your GPF account has been <b>Activated</b>.',
				'subject' => 'Two-Factor Authentication Enabled'
			];
			Mail::to(auth()->user()->email)->send(new SendMail($input));
			$msg = 'Two factor login activated successfully';
        } else {
			$statusCode = 422;
			$data['data']['error']= 'Invalid authentication code.';
			$msg= 'Invalid authentication code.';
        }
        return Api::ApiResponse($data,$statusCode,$msg);
    }


    public function disableTwoFactor()
    {
        auth()->user()->update([
            'google2fa_secret' => null
        ]);
		$input = [
			'user_id' => auth()->id(),
			'mail' => 'The Two Factor Authentication of your GPF account has been <b>Deactivated</b>.',
			'subject' => 'Two-Factor Authentication Disabled'
		];
		Mail::to(auth()->user()->email)->send(new SendMail($input));
        $data['data'] = [];
        return Api::ApiResponse($data,200,'Two factor login deactivated successfully');
    }

    public function uploadProfilePic(UpdateProfilePicRequest $request)
    {
        $res = base64ToJpeg(request('image'), 'profile');
        $userObj = User::where('id',auth()->id())->first();
        $userObj->image_name = isset($res['media_name']) ? $res['media_name'] : NULL;
        $userObj->image_path = isset($res['media_path']) ? $res['media_path'] : NULL;
        $userObj->save();

        $data['data']['user'] = ProfileTransformer::transform($userObj->toArray());
        return Api::ApiResponse($data);
    }

    public function show()
    {
        $user = auth()->user();
       
        $mobile = UserMobile::where('user_id', '=', $user->id)
        ->orderBy('id', 'desc')
        ->first();

        $user = auth()->user();
        if($mobile != NULL){
            $user->cd_phone = $mobile->new_mobile;
          $user->cd_phone_verified_at = NULL;
        }
        $data['data']['site']=[
          'otpExpires' => config('site.otp_disable'),
          'callExpires' => config('site.call_disable'),
          'otpExpiresInSeconds' => (config('site.otp_disable')*60) * 1000,
          'callExpiresInSeconds' => (config('site.call_disable')*60) * 1000,
        ];
        $data['data']['country'] = Country::select('nice_name as name', 'id as value')->get();
        $data['data']['country_code'] = Country::select('country_code as name', 'country_code as value')->orderBy('country_code')->get();
        $data['data']['user'] = ProfileTransformer::transform($user->toArray());
        $data['data']['payment'] = getPaymentDetail();
        return Api::ApiResponse($data);
    }

    public function update(UpdateRequest $request)
    {
        $phone = request('cd_phone');

        $user = auth()->user();
        $cdPhone = $user->cd_phone;
		$country = Country::where('country_code', '=', $request->cd_country_code)->first();
		$request->request->add(['cd_country'=>$country->id]);
        $user->update($request->except('cd_phone'));

         $mobile = UserMobile::where('user_id', '=', $user->id)
        ->where('new_mobile',$phone)
        ->orderBy('id', 'desc')
        ->first();
        $user->save();

        if($mobile == NULL && $user->cd_phone != $phone){

          UserMobile::where('user_id',$user->id)
                    ->update([
                            'deleted_at'=>date_time_database('now')
                            ]);

          UserMobile::create([
            'user_id' => $user->id,
            'new_mobile' => $phone
          ]);
           $user->cd_phone_verified_at = NULL;
           $user->save();
           $user->cd_phone = $phone;

        }

        $data['data'] = ProfileTransformer::transform($user->toArray());
        $msg = 'Profile Updated Successfully';
        return Api::ApiResponse($data,200,$msg);
    }

    public function sendOtp()
    {
        $data['data'] = [];
        $user = auth()->user();

        $user->sendOtp(true);
        return Api::ApiResponse($data);
    }

	public function getAuthyStatus()
    {
        $loggedInUser =  auth()->user();
        $data['data']['authy_required'] = false ;
        if($loggedInUser->google2fa_secret != null){
          $data['data']['authy_required'] = true ;
        }
        return Api::ApiResponse($data);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
      $user  = auth()->user();
        $userOtp = UserMobile::where('user_id', '=', $user->id)
            ->orderBy('id','desc')
            ->first();
      if($userOtp == ''){
        $statusCode = 422;
        $data['data']['error'] = 'Invalid Mobile Number.';
        return Api::ApiResponse($data,$statusCode);
      }
        $otp = Otp::where('user_id', '=', $user->id)
            ->where('otp',$userOtp['otp'])
            ->where('created_at', '>=', date_time_database('-' . config('site.otp_expire') . ' min'))
            ->orderBy('id','desc')
            ->first();

        if ($otp == null) {
            $statusCode = 422;
            $data['data']['error'] = 'Your otp is expired.Please click <a href="' . url()->route('users.verification.mobile', $user->id) . '">here</a> to resend it.';
            return Api::ApiResponse($data,$statusCode);
        }
        $otpInput = explode(",",request('otp'));
        if ($otp->otp != implode('', $otpInput)) {
          $statusCode = 422;
          $data['data']['error'] = 'Invalid OTP.';
          return Api::ApiResponse($data,$statusCode);
        }
        $user->update([
            'cd_phone' => $userOtp['new_mobile'],
            'cd_phone_verified_at' => date_time_database('now')
        ]);
        $otp->delete();
        $userOtp->delete();

        $user->successfullRegistrationMail();
        $user->update([
            'last_activity' => date_time_database('now')
        ]);
        $data['data'] = [];
      return Api::ApiResponse($data);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (!(Hash::check($request->get('current_password'), $user->password))) {
          $statusCode = 422;
          $data['data']['error'] = "Your current password does not matches with the password you provided. Please try again.";
          return Api::ApiResponse($data,$statusCode);
        }
        if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
             $statusCode = 422;
             $data['data']['error'] = "New Password cannot be same as your current password. Please choose a different password.";
             return Api::ApiResponse($data,$statusCode);
         }
        $user->password = request('new_password');
        $user->save();
        $data['data'] =  $user->toArray();
        return Api::ApiResponse($data);
    }


    public function actionBox()
    {
        $user = auth()->user();
        $pagination = config("site.pagination");
        $data[] = [];
		$page = request('page') ? request('page') : 1;
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
    		$count = $pagination ;
        $ships = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')->where('user_id', '=', $user->id)
            ->where('status', '=', "1");
        if(request('page')){
          $ships->take($count)
          ->skip($start);
        }
        $shipments = $ships->orderBy('id','desc')->get();

        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
		$data['data']['shipment'] = [];
        $shipment_ids = $shipments->pluck('id');
        $shipment_items = ShipmentItem::whereIn('shipment_id', $shipment_ids)
            ->get();
        $shipment_items_ids = $shipment_items->pluck('id')->merge($shipment_ids);
        $medias = Media::whereIn('main_id', $shipment_items_ids)
            ->whereIn('type', ['Shipment', 'ShipmentItem'])
            ->get();
        $shipment_items = $shipment_items->map(function ($item, $key) use ($medias) {
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'ShipmentItem');
            return $item;
        });
        $shipmentObject = $shipments->map(function ($item, $key) use ($shipment_items, $medias,$user) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'Shipment');
            return $item;
        });

        foreach($shipmentObject as $key => $value){
          $data['data']['shipment'][] = ActionBoxTransformer::transform($value);
        }
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);
        return Api::ApiResponse($data);
    }

    public function warehouse()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $data = [];
        $pagination = config("site.pagination");
			$page = request('page') ? request('page') : 1;
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
    		$count = $pagination ;
        $ships = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*','invoices.invoice_name','invoices.invoice_path')
            ->leftJoin('invoices',function($join){
              $join->on('invoices.entity_id','=','shipments.id')
                ->where('invoices.entity_type',1);
            })
            ->where('shipments.user_id', '=', $user->id)
            ->where('status', '=', 2);
        if(request('page')){
          $ships->take($count)
            ->skip($start);
        }
        $shipments = $ships->orderBy('id','desc')->get();
        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $shipment_ids = $shipments->pluck('id');
        $shipment_items = ShipmentItem::whereIn('shipment_id', $shipment_ids)
            ->get();
        $shipment_items_ids = $shipment_items->pluck('id')->merge($shipment_ids);
        $medias = Media::whereIn('main_id', $shipment_items_ids)
            ->whereIn('type', ['Shipment', 'ShipmentItem'])
            ->get();

        $shipmentObject = $shipments->map(function ($item, $key) use ($shipment_items, $medias) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'Shipment');
            return $item;
        });
        foreach($shipmentObject as $key => $value){
          $shipmentObj[] = WarehouseTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);
        return Api::ApiResponse($data);
    }

    public function readyForShipping()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $pagination = config("site.pagination");
		    $page = request('page') ? request('page') : 1;
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $ships = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')
            ->where('user_id', '=', $user->id)
            ->where('status', '=', 3)
            ->orWhere('status', '=', 4);
        if(request('page')){
          $ships->take($count)
            ->skip($start);
        }
        $shipments = $ships->orderBy('id','desc')->get();

        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        foreach($shipments as $key => $value){
          $shipmentObj[] = ReadyForShippingTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);

		$msg = '';

		if(session()->has('payment')){
			\Log::info("Inside Session");
			$payment = session()->pull('payment');
			$msg = 'Your payment has been processed successfully';
		}
        return Api::ApiResponse($data,200,$msg);
    }

    public function shipments()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $pagination = config("site.pagination");
		$page = request('page') ? request('page') : 1;
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $ships = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))->where('shipments.user_id', '=', $user->id)
            ->leftJoin('shipment_histories',function($join){
                $join->on('shipment_histories.shipment_id','=','shipments.id')
                    ->where('shipment_histories.status_id','1');
            })
            ->leftJoin('users','users.id','=','shipment_histories.user_id')
            ->where('status', '=', '5');
        if(request('page')){
          $ships->take($count)
            ->skip($start);
        }
        $shipments = $ships->orderBy('shipments.id','desc')->get();
		$total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;

		$shipment_ids = $shipments->pluck('id');
        $shipment_items = ShipmentItem::whereIn('shipment_id', $shipment_ids)
            ->get();

        $shipmentObject = $shipments->map(function ($item, $key) use ($shipment_items) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            return $item;
        });

        foreach($shipments as $key => $value){
          $shipmentObj[] = ShipmentTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);
		return Api::ApiResponse($data);

    }

    public function history()
    {
        $user = auth()->user();
        $shipmentObj = [];
		$page = request('page') ? request('page') : 1;

        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')->where('user_id', '=', auth()->user()->id)
            ->where('status', '=', '6')
            ->take($count)
            ->skip($start)
            ->orderBy('id','desc')
            ->get();
        $total = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        foreach($shipments as $key => $value){
          $shipmentObj[] = HistoryTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
		$data['data']['page'] = $page+1;
		$data['data']['pagination'] = getPaginationObject($total,$start,$count);
        return Api::ApiResponse($data);
    }


    public function requestItemPhoto($id,$item)
    {
        $user = auth()->user();
        $shipment = Shipment::find($id);
        if ($shipment == '') {
          $statusCode = 422;
          $data['data']['error'] = "Shipment Not Found";
          return Api::ApiResponse($data,$statusCode);
        }
        $shipmentItem = ShipmentItem::find($item);
        if ($shipmentItem == '') {
          $statusCode = 422;
          $data['data']['error'] = "Shipment Item Not Found";
          return Api::ApiResponse($data,$statusCode);
        }
        if ($shipment->id == $shipmentItem->shipment_id && $shipment->user_id == $user->id) {
            $data = [];
            if ($user->plan_type == 'free') {
                $data['data'] = [
                  'status' => false,
                  ];
                $info = 'Please upgrade your plan to access this feature.';
            } else {
                $admins = User::role(['Admin','Super Admin'])->get();
                PhotoRequest::create([
                  'user_id' => $user->id,
                  'shipment_item_id' => $shipmentItem->id
                ]);
                Notification::send($admins, new RequestShipmentItemPhoto($shipment, $shipmentItem, $user));
                $data['data'] = [
                  'status' => true,
                  ];
                  $info = 'Request sent successfully.We will get back to you soon.';
            }
            return Api::ApiResponse($data,200,$info);
        } else {
          $statusCode = 422;
          $data['data']['error'] = "Shipment Item Not available in Provided Shipment";
          return Api::ApiResponse($data,$statusCode);
        }
    }

    public function shippingOptions($id, SelectShippingOptionRequest $request)
    {
        $user = auth()->user();
        $shipment = Shipment::find($id);
        $data['data'] = [];
        if ($shipment == '') {
          $statusCode = 422;
          $data['data']['error'] = "Shipment Not Found";
          return Api::ApiResponse($data,$statusCode);
        }
        $amount=0;
        $insurance_charge=0;
        if($request['insuranceOption'])
        {
          $items=ShipmentItem::where('shipment_id',$id)->get();
          foreach($items as $key=>$value)
          {
            $amount+=$value['amount'];
          }
          $insurance_charge=$amount*config('site.insurance_charge')/100;
          $total=$request['total_price'] +  $insurance_charge;
        }
        else
        {
          $total=$request['total_price'];
        }

        $shipment->update([
            'shipping_out_company' => $request['name'],
            'shipping_out_service' => $request['service'],
            'shipping_out_amount'  => $request['price'],
            'gpf_charge'  => $request['gpf_charges'],
            'insurance_charge' =>   $insurance_charge,
            'total'  => $total,
            'shipping_out_logo'    => $request['logo'],
            'status'               => '2',
        ]);
        addShipmentStatus($shipment->id, 2,$user->id, $request['name'] . ' is selected as shipping option.');
        $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
        ->where(DB::raw('year(created_at)'), '=', date('Y'))
        ->count();
        $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
        $invoiceObj = Invoice::create([
          'entity_type' => 1,
          'entity_id' => $shipment->id,
          'user_id' => $shipment->user_id,
          'total' => $shipment->total,
          'invoice_number' => $invoiceNumber,
          'due_date' => date('Y-m-d', strtotime(' +1 day'))
        ]);
        $invoice = generateInvoice($shipment->id);
        $invoiceObj->invoice_name = $invoice['invoice_name'];
        $invoiceObj->invoice_path = $invoice['invoice_path'];
        $invoiceObj->save();
        return Api::ApiResponse($data);
    }

    public function readNotification()
    {
        if(request('notification_id')){
          auth()->user()->notifications()->where('id', '=', request('notification_id'))->update([
            'read_at' => date_time_database('now')
          ]);
        }else{
          auth()->user()->notifications()->update([
            'read_at' => date_time_database('now')
          ]);
        }
		$data['data']['count']  = auth()->user()->unreadNotifications()->whereIn('type',['App\Notifications\ShipmentCreated','App\Notifications\QuotationReponseFromAdmin'])->count();
        return Api::ApiResponse($data);
    }
}
