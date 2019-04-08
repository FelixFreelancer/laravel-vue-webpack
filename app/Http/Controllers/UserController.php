<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Media;
use App\Models\SecurityQuestion;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\PhotoRequest;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserSecurityQuestion;
use App\Notifications\RequestShipmentItemPhoto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use SEO;

class UserController extends Controller
{
    public function profile()
    {
        $data = [];
        $data['genders'] = collect(config('site.gender'))->map(function ($item, $key) {
            return ucwords($item);
        });
        $otp = session()->pull('otp_verified');
        if($otp != ''){
          $data['verify'] = true;
          $data['title'] = 'OTP verified successfully';
          $data['message'] = 'Thank you for verifying your
phone number. Your account is now fully active';
        }
        $data['countries'] = Country::pluck('nice_name', 'id');
        $data['country_codes'] = Country::orderBy('country_code')->pluck('country_code', 'id');
		SEO::setTitle('Profile Summary | GlobalParcelForward');
        SEO::opengraph()->setUrl(url('/dashboard/#/profile-summary'));
        SEO::setCanonical(url('/dashboard/#/profile-summary'));
        return view('frontend.pages.users.profile', $data);
    }

    public function profileUpdate()
    {
        $this->validate(request(), User::profileValidationRules('cd', auth()->user()->id));

        if (!auth()->user()->update(request()->all())) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }
        session()->flash('message', 'Profile updated successfully.');
        session()->flash('class', 'success');
        return back();
    }

    public function checkMobile()
    {
        $user = auth()->user();
        $data['status'] = false;
        if($user->cd_phone != request('phone') && request('phone')!='' && request('isOtpVerified') == 'false'){
          $data['status'] = true;
        }
        return $data;
    }

    public function sendOtp()
    {
        $data['status'] = true;
        $user = auth()->user();
        $user->sendOtp();
        return $data;
    }

    public function otpVerificationCheck()
    {
        $user = auth()->user();
        $validator = Validator::make(request()->all(), [
            'otp' => 'required'
        ]);
        if ($validator->fails()) {
            $data['status'] = false;
            $data['message'] = 'The otp field is required';
            $data['class'] = 'error';
        } else {

          $otp = Otp::where('user_id', '=', $user->id)
              ->where('created_at', '>=', date_time_database('-' . config('site.otp_expire') . ' min'))
              ->first();

          if ($otp == null) {
              $data['status'] = false;
              $data['message'] = 'Your otp is expired. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.';
              $data['class'] = 'error';
              return $data;
          }
          $otpInput = explode(",",request('otp'));
          if ($otp->otp != implode('', $otpInput)) {
            $data['status'] = false;
              $data['message'] ='Entered otp is wrong. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.';
              $data['class'] ='error';
              return $data;
          }
          $user->update([
              'cd_phone' => request('phone'),
              'cd_phone_verified_at' => date_time_database('now')
          ]);
          $otp->delete();
          $data['message'] = 'Your mobile number is verified.';
          $data['class'] = 'success';
          $data['status'] = true;

          auth()->loginUsingId($user->id);
          auth()->user()->successfullRegistrationMail();
          auth()->user()->update([
              'last_activity' => date_time_database('now')
          ]);
        }
        return $data;
    }

    public function security()
    {
        $data = [];
        $data['security_questions'] = SecurityQuestion::pluck('questions', 'id');
        $data['current_question'] = UserSecurityQuestion::where('user_id', '=', auth()->user()->id)
            ->first();
        return view('frontend.pages.users.security', $data);
    }

    public function securityUpdate()
    {
        $this->validate(request(), User::profileValidationRules('security', auth()->user()->id));

        if (request('password') && request('password') != '') {
            if (!auth()->user()->update(request()->only('password'))) {
                session()->flash('message', 'Something went wrong.');
                session()->flash('class', 'danger');
                return back();
            }
        }
        UserSecurityQuestion::where('user_id', '=', auth()->user()->id)->delete();
        UserSecurityQuestion::create([
            'user_id'              => auth()->user()->id,
            'security_question_id' => request('security_question'),
            'answer'               => request('answer')
        ]);
        session()->flash('message', 'Password updated successfully.');
        session()->flash('class', 'success');
        return back();
    }

    public function enableTwoFactor()
    {
        $authy_api = authyApi();

        $country_code = Country::where('id', '=', auth()->user()->cd_country_code)->first();

        $user = $authy_api->registerUser(auth()->user()->email, auth()->user()->cd_phone, $country_code->country_code); //email, cellphone, country_code

        if ($user->ok()) {
            auth()->user()->update([
                'google2fa_secret' => $user->id()
            ]);
        } else {
            Log::info($user->errors());
        }
        return back();
    }

    public function disableTwoFactor()
    {
        auth()->user()->update([
            'google2fa_secret' => null
        ]);

        return back();
    }

    public function actionbox()
    {
        $data = [];
        $shipments = Shipment::where('user_id', '=', auth()->user()->id)
            ->where('status', '=', '1')
            ->get();
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
        $data['shipments'] = $shipments->map(function ($item, $key) use ($shipment_items, $medias) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'Shipment');
            return $item;
        });
        $data['country'] = Country::find(auth()->user()->cd_country);
        return view('frontend.pages.users.action_box', $data);
    }

    public function requestItemPhoto(Shipment $shipment, ShipmentItem $shipment_item)
    {
        if ($shipment->id == $shipment_item->shipment_id && $shipment->user_id == auth()->user()->id) {
            $data = [];
            if (auth()->user()->plan_type == 'free') {
                $data['status'] = false;
                $data['message'] = 'Please upgrade your plan for accessing this feature.';
            } else {
                $data['status'] = true;
                $admins = User::role(['Admin','Super Admin'])->get();
                PhotoRequest::create([
                  'user_id' => auth()->id(),
                  'shipment_item_id' => $shipment_item->id
                ]);
                Notification::send($admins, new RequestShipmentItemPhoto($shipment, $shipment_item, auth()->user()));
            }
            return $data;
        } else {
            abort('404');
        }
    }

    public function itemPhotos(Shipment $shipment, ShipmentItem $shipment_item)
    {
        if ($shipment->id == $shipment_item->shipment_id && $shipment->user_id == auth()->user()->id) {
            $data = [];
            if (auth()->user()->plan_type == 'free') {
                $data['status'] = false;
                $data['message'] = 'Please upgrade your plan for accessing this feature.';
            } else {
                $data['status'] = true;
                $media = Media::where('main_id', '=', $shipment_item->id)
                    ->whereIn('type', ['ShipmentItem'])
                    ->get();
                $media = $media->map(function ($item, $key) {
                    $item->media_name = asset($item->media_path . $item->media_name);
                    return $item;
                });
                $data['medias'] = $media;
            }
            return $data;
        } else {
            abort('404');
        }
    }

    public function downGradeAccount()
    {
        auth()->user()->update([
            'plan_type' => 'free'
        ]);
        return back();
    }

    public function subscription()
    {
        return view('frontend.pages.users.subscription');
    }

    public function autoRenew()
    {
        $status = request('status');
        if ($status != 1 || $status != 0) {
            $status = 0;
        }
        $data['status'] = auth()->user()->update([
            'auto_renew' => $status
        ]);
        return $data;
    }

    public function shippingOption(Shipment $shipment)
    {
        $this->validate(request(), Shipment::shippingOptionvalidationRules());

        $shipment->update([
            'shipping_out_company' => request('company_name'),
            'shipping_out_service' => request('service_name'),
            'shipping_out_amount'  => request('shipment_price'),
            'shipping_out_logo'    => request('company_logo'),
            'status'               => '2',
        ]);
        addShipmentStatus($shipment->id, 2,auth()->id(), request('company_name') . ' is selected as shipping option.');

        session()->flash('message', 'Shipping options is selected.');
        session()->flash('class', 'success');

        return redirect()->route('users.warehouse');
    }

    public function warehouse()
    {
        $data = [];
        $shipments = Shipment::where('user_id', '=', auth()->user()->id)
            ->where('status', '=', '2')
            ->get();
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
        $data['shipments'] = $shipments->map(function ($item, $key) use ($shipment_items, $medias) {
            $item->items = $shipment_items->where('shipment_id', '=', $item->id);
            $item->medias = $medias->where('main_id', '=', $item->id)->where('type', '=', 'Shipment');
            return $item;
        });
        $data['country'] = Country::find(auth()->user()->cd_country);
        return view('frontend.pages.users.warehouse', $data);
    }

    public function warehouseOption(Shipment $shipment)
    {
        $shipment->update([
            'status'               => '4'
        ]);
        return redirect()->route('users.shipments.ready-for-shipment');
    }

    public function dashboardShipping()
    {
        $data['shipments']  = Shipment::where('user_id', '=', auth()->user()->id)
            ->where('status', '=', '3')
            ->orWhere('status', '=', '5')
            ->get();
        return view('frontend.pages.users.ready-for-shipping',$data);
    }

    public function dashboardShipments()
    {
      $shipments = Shipment::where('user_id', '=', auth()->user()->id)
          ->where('status', '=', '6')
          ->get();
      $shipment_ids = $shipments->pluck('id');
      $shipment_items = ShipmentItem::whereIn('shipment_id', $shipment_ids)
          ->get();
      $data['shipments'] = $shipments->map(function ($item, $key) use ($shipment_items) {
          $item->items = $shipment_items->where('shipment_id', '=', $item->id);
          return $item;
      });
        return view('frontend.pages.users.dashboard-shipments',$data);
    }

    public function dashboardHistory()
    {
      $data['shipments'] = Shipment::where('user_id', '=', auth()->user()->id)
          ->where('status', '=', '7')
          ->get();
        return view('frontend.pages.users.dashboard-history',$data);
    }


    public function dashboardInvoice()
    {
        return view('frontend.pages.dashboard-invoice');
    }


}
