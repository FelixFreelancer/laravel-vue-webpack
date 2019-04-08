<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\User\UpdateRequest;
use App\Http\Requests\Front\Shipment\SelectShippingOptionRequest;
use App\Http\Requests\Front\User\VerifyOtpRequest;
use App\Http\Requests\Front\User\SendOtpRequest;
use App\Transformers\ReadyForShippingTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\ActionBoxTransformer;
use App\Transformers\WarehouseTransformer;
use App\Transformers\ShipmentTransformer;
use App\Transformers\HistoryTransformer;
use App\Models\User;
use App\Models\Media;
use App\Models\PhotoRequest;
use App\Models\Otp;
use App\Models\Country;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestShipmentItemPhoto;
use App\Library\Api;
use Hash;
use DB;

class UserController extends Controller
{

    public function show()
    {
        $user = auth()->user();
        $data['data'] = $user->toArray();
        return Api::ApiResponse($data);
    }

    public function update(UpdateRequest $request)
    {
        $user = auth()->user();
        $user->update($request->all());
        $data['data'] = $user->toArray();
        return Api::ApiResponse($data);
    }

    public function sendOtp()
    {
        $data['data'] = [];
        $user = auth()->user();
        $user->sendOtp();
        return Api::ApiResponse($data);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
      $user  = auth()->user();
        $otp = Otp::where('user_id', '=', $user->id)
            ->where('created_at', '>=', date_time_database('-' . config('site.otp_expire') . ' min'))
            ->first();

        if ($otp == null) {
            $statusCode = 422;
            $data['data']['error'] = 'Your otp is expired. Please click here to resend it.';
            return Api::ApiResponse($data,$statusCode);
        }
        $otpInput = explode(",",request('otp'));
        if ($otp->otp != implode('', $otpInput)) {
          $statusCode = 422;
          $data['data']['error'] = 'Entered otp is wrong.';
          return Api::ApiResponse($data,$statusCode);
        }
        $user->update([
            'cd_phone' => $request['phone'],
            'cd_phone_verified_at' => date_time_database('now')
        ]);
        $otp->delete();

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
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
    		$count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')->where('user_id', '=', $user->id)
            ->where('status', '=', "1")
            ->take($count)
            ->skip($start)
             ->get();

        if(count($shipments) == 0){
          $statusCode = 422;
          $data['data']['error'] = "Shipment not found";
          return Api::ApiResponse($data,$statusCode);
        }
        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
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
        $shipmentObject = $shipments->map(function ($item, $key) use ($shipment_items, $medias) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'Shipment');
            return $item;
        });
        foreach($shipmentObject as $key => $value){
          $data['data']['shipment'][] = ActionBoxTransformer::transform($value);
        }
        $data['data']['country'] = Country::find($user->cd_country);
        return Api::ApiResponse($data);
    }

    public function warehouse()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $data = [];
        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
    		$count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')
            ->where('user_id', '=', $user->id)
            ->where('status', '=', 2)
            ->take($count)
            ->skip($start)
            ->get();

        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
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
        return Api::ApiResponse($data);
    }

    public function readyForShipping()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')
            ->where('user_id', '=', $user->id)
            ->where('status', '=', 3)
            ->orWhere('status', '=', 4)
            ->take($count)
            ->skip($start)
            ->get();

        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        foreach($shipments as $key => $value){
          $shipmentObj[] = ReadyForShippingTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
        return Api::ApiResponse($data);
    }

    public function shipments()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')->where('user_id', '=', $user->id)
            ->where('status', '=', '5')
            ->take($count)
            ->skip($start)
            ->get();
        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        foreach($shipments as $key => $value){
          $shipmentObj[] = ReadyForShippingTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
        return Api::ApiResponse($data);
    }

    public function history()
    {
        $user = auth()->user();
        $shipmentObj = [];
        $pagination = config("site.pagination");
        $start = (request('page') ? ((request('page') - 1) * $pagination) : '0' );
        $count = $pagination ;
        $shipments = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*')->where('user_id', '=', auth()->user()->id)
            ->where('status', '=', '6')
            ->take($count)
            ->skip($start)
            ->get();
        $data['data']['total'] = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        foreach($shipments as $key => $value){
          $shipmentObj[] = HistoryTransformer::transform($value);
        }
        $data['data']['shipment'] = $shipmentObj;
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
                  'message' => 'Please upgrade your plan for accessing this feature.',
                  ];
            } else {
                $admins = User::role(['Admin','Super Admin'])->get();
                PhotoRequest::create([
                  'user_id' => $user->id,
                  'shipment_item_id' => $shipmentItem->id
                ]);
                Notification::send($admins, new RequestShipmentItemPhoto($shipment, $shipmentItem, $user));
                $data['data']['status'] = true;
            }
            return Api::ApiResponse($data);
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
        $shipment->update([
            'shipping_out_company' => $request['company_name'],
            'shipping_out_service' => $request['service_name'],
            'shipping_out_amount'  => $request['shipment_price'],
            'gpf_charge'  => $request['gpf_charge'],
            'total'  => $request['total'],
            'shipping_out_logo'    => $request['company_logo'],
            'status'               => '2',
        ]);
        addShipmentStatus($shipment->id, 2,$user->id, $request['company_name'] . ' is selected as shipping option.');

        return Api::ApiResponse($data);
    }

}
