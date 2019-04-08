<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ListRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Requests\Admin\User\ChangePasswordRequest;
use App\Http\Requests\Admin\User\TwoFactorAuthRequest;
use App\Http\Requests\Admin\User\UpdateStatusRequest;
use App\Http\Requests\Admin\User\VerifyUserRequest;
use App\Http\Requests\Front\User\Verify2FARequest;
use App\Http\Requests\ReadNotificationRequest;
use App\Http\Requests\Front\User\UpdateProfilePicRequest;
use App\Transformers\ProfileTransformer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\UserSecurityQuestion;
use App\Models\UserMobile;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\ShipmentHistory;
use App\Models\ContactUs;
use App\Models\Log;
use App\Models\UserMail;
use App\Models\Otp;
use App\Models\PhotoRequest;
use App\Models\Country;
use App\Transformers\Admin\CustomerTransformer;
use App\Library\Api;
use DB;
use Hash;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

/**
 * @resource User
 *
 */

class UserController extends Controller
{
    /**
     * List User
     *
     * This endpoint will list the user.
     * Specify role id for get role specific users.
     * If you want only particular fields then specify it in "field" parameter otherwise you will get whole object.
     */
    public function index(ListRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $roles = config('site.roles');
        $rst = User::search(request()->all());
        if(isset($request['query'])){
          if((isset($request['query']['type']) && $request['query']['type'] == 'customer') || (isset($request['query']['role_id']) && $request['query']['role_id'] == '2')){
            $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
            addLog($loggedInUser['user']['id'],'Customer Listing','<b>'.$name."</b> has viewed <b>Customer Tab</b>.");
          }
        }
        foreach ($rst['users'] as $key => $value) {
            $rst['users'][$key]['gender'] = $value->gender == 1 ? 'Male' : ( $value->gender == 2 ? 'Female' : '');
            $rst['users'][$key]['role'] = implode(", ",$value->getRoleNames()->toArray());
            $rst['users'][$key]['contact_number'] = $value['cd_country_code']." ".$value['cd_phone'];
            $rst['users'][$key]['contact_no'] = $value['ba_country_code']." ".$value['ba_phone'];
            $rst['users'][$key]['name'] = $value['first_name']." ".$value['last_name'];
            $rst['users'][$key]['registered_on'] = strtotime($value['created_at']);
            $rst['users'][$key]['membership_validity'] = $value['membership_validity'] != NULL ? strtotime($value['membership_validity']) : NULL;
            $rst['users'][$key]['profile_pic'] = $value['image_name'] != NULL ? asset($value['image_path'].$value['image_name']) : NULL;
            $rst['users'][$key]['twofa'] = $value['google2fa_secret'] != NULL ? true : false;
            if($value->email_verified_at == NULL && $value->cd_phone_verified_at == NULL){
              $rst['users'][$key]['user_type'] = 'Non-Activated';
              $rst['users'][$key]['verify'] = false;
            }else if($value->email_verified_at != NULL && $value->cd_phone_verified_at == NULL){
              $rst['users'][$key]['user_type'] = 'Pre-Activated';
              $rst['users'][$key]['verify'] = false;
            }else{
              $rst['users'][$key]['user_type'] = 'Activated';
              $rst['users'][$key]['verify'] = true;
            }
            $rst['users'][$key]['user_type'] = getUserStatus($value);
            $rst['users'][$key]['verify'] = getUserVerificationStatus($value);
            unset($value->roles);
        }

        $data['data'] = $rst;

        return Api::ApiResponse($data);
    }

    public function uploadProfilePic(UpdateProfilePicRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        $res = base64ToJpeg(request('image'), 'profile');
        $loggedInUser['user']->image_name = isset($res['media_name']) ? $res['media_name'] : NULL;
        $loggedInUser['user']->image_path = isset($res['media_path']) ? $res['media_path'] : NULL;
        $loggedInUser['user']->save();

        $data['data']['user'] = ProfileTransformer::transform($loggedInUser['user']->toArray());
        return Api::ApiResponse($data);
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
		$user = User::find($request['user_id']);
        $user->is_active = $request['status'];
        $user->save();
		$msg = $user['first_name']." ".$user['last_name'];
		if($request['status'] == "1"){
			$msg .= ' activated ';
		}else{
			$msg .= ' deactivated ';
		}
		$msg .= 'successfully';
        $data['data']['user'] = ProfileTransformer::transform($user->toArray());
        return Api::ApiResponse($data,200,$msg);
    }

	public function enableTwoFactor()
	{
		$google2fa = app('pragmarx.google2fa');
		$secret = $google2fa->generateSecretKey();
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
			$loggedInUser = Api::getAuthenticatedUser();
			$loggedInUserObj = $loggedInUser['user'];
			$loggedInUserObj->update([
				'google2fa_secret' => $request->secret
			]);
			$input = [
				'user_id' => $loggedInUserObj['id'],
				'mail' => 'The Two Factor Authentication of your GPF account has been <b>Activated</b>.',
				'subject' => 'Two-Factor Authentication Enabled'
			];
			Mail::to($loggedInUserObj['email'])->send(new SendMail($input));
			$msg = 'Two factor login activated successfully';
	    } else {
			$statusCode = 422;
			$data['data']['error']= 'Invalid authentication code.';
			$msg= 'Invalid authentication code.';
	    }
	    return Api::ApiResponse($data,$statusCode,$msg);
	}


	public function disableTwoFactor(TwoFactorAuthRequest $request)
	{
		$user = User::find($request['user_id']);
		$user->update([
			'google2fa_secret' => null
		]);
		$input = [
			'user_id' => $user->id,
			'mail' => 'The Two Factor Authentication of your GPF account has been <b>Deactivated</b>.',
			'subject' => 'Two-Factor Authentication Disabled'
		];
		Mail::to($user->email)->send(new SendMail($input));
		$loggedInUser = Api::getAuthenticatedUser();
		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		$userName = $user['first_name']." ".$user['last_name'];
		addLog($loggedInUser['user']['id'],'2FA Disabled','<b>'.$name.'</b> has disalbed 2FA for <b>'.$userName.' ['.$user['customer_code'].']</b>.');

		$data['data'] = [];
		return Api::ApiResponse($data,200,'Two factor authentication deactivated successfully');

	}


    public function verifyUserRequest(VerifyUserRequest $request)
    {
        $user = User::find($request['user_id']);
        $input['cd_phone_verified_at'] = date('Y-m-d H:i:s');
        if($user['email_verified_at'] == NULL){
          $input['email_verified_at'] = date('Y-m-d H:i:s');
        }
        $user->update($input);

		$loggedInUser = Api::getAuthenticatedUser();
  		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
  		$userName = $user['first_name']." ".$user['last_name'];
  		addLog($loggedInUser['user']['id'],'User Verification','<b>'.$name.'</b> has verified <b>'.$userName.' ['.$user['customer_code'].']</b>.');

        $data['data'] = [];
        return Api::ApiResponse($data,200,'User verified successfully');
    }

    public function unVerifyUserRequest(VerifyUserRequest $request)
    {
        $user = User::find($request['user_id']);
        $user->update([
            'email_verified_at' => NULL,
            'cd_phone_verified_at' => NULL
        ]);

		$loggedInUser = Api::getAuthenticatedUser();
		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		$userName = $user['first_name']." ".$user['last_name'];
		addLog($loggedInUser['user']['id'],'User Unverification','<b>'.$name.'</b> has unverified <b>'.$userName.' ['.$user['customer_code'].']</b>.');

        $data['data'] = [];
        return Api::ApiResponse($data,200,'User unverified successfully');
    }

    public function getAuthyStatus()
    {
        $loggedInUser =  Api::getAuthenticatedUser();
        $data['data']['authy_required'] = false ;
        if($loggedInUser['user']->google2fa_secret != null){
          $data['data']['authy_required'] = true ;
        }
        return Api::ApiResponse($data);
    }
    /**
     * Store User
     *
     * This endpoint will create user
     */
    public function store(StoreRequest $request)
    {
        $user = User::create(request()->only('first_name','last_name', 'email','cd_phone', 'password'));
        $role = Role::whereIn('id',request('role_id'))->pluck('name');
        $user->assignRole($role);
        $userName = $user->first_name.' '.$user->last_name;
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'User Store','New user <b>['.$userName.']</b> has been added by <b>'.$name.'</b>');

        $data['data'] = $user->toArray();
        return Api::ApiResponse($data);
    }
    /**
     *  User Detail
     *
     * This endpoint will return the detail of user.
     */
    public function show($user)
    {
      $loggedInUser = Api::getAuthenticatedUser();
        $userObj = User::leftJoin('countries','countries.id','=','users.cd_country')
            ->leftJoin('countries as c2','c2.id','=','users.ba_country')
            ->select('users.*','countries.nice_name as cd_country_name','c2.nice_name as ba_country_name','countries.suite_number as suite_number')
            ->where('users.id',$user)
            ->first();
        $statusCode = 200;
        $data['data'] = [];
        if(!$userObj){
          $statusCode = 422;
          $data['data']['error'] = "User Not Found";
        }else{
          if($userObj->hasRole('Customer') && !request('edit')){
            $name = $userObj['first_name']." ".$userObj['last_name'];
            $userName = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
            addLog($loggedInUser['user']['id'],'Customer View','<b>'.$userName."</b> has viewed Customer <b>".$name ." [". $userObj['customer_code']."].</b>");
          }
          $userObj->role = $userObj->roles->pluck('id')->toArray();
		  $userObj->gender_val = $userObj->gender;
          $userObj->gender = $userObj->gender == 1 ? 'Male' : ( $userObj->gender == 2 ? 'Female' : '');
          $userObj->contact_number = $userObj->cd_country_code." ".$userObj->cd_phone;
          $userObj->contact_no = $userObj->ba_country_code." ".$userObj->ba_phone;
          $userObj->registered_on = strtotime($userObj->created_at);
          $userObj->membership_validity = strtotime($userObj->membership_validity);
          $userObj->profile_pic = $userObj->image_name != NULL ? asset($userObj->image_path.$userObj->image_name) : NULL;
          $userObj->twofa = $userObj->google2fa_secret != NULL ? true : false;
          $userObj->user_type = getUserStatus($userObj);
          $userObj->verify = getUserVerificationStatus($userObj);
          $userObj->security_question = UserSecurityQuestion::leftJoin('security_questions','security_questions.id','=','user_security_questions.security_question_id')
		  								->select('user_security_questions.id','security_questions.questions','answer')
										->where('user_id',$user)
										->get();
			$userObj->genders = collect(config('site.gender'))->map(function ($item, $key) {
	            return ucwords($item);
	        });
          unset($userObj->roles);
          $data['data'] = $userObj->toArray();
        }
        return Api::ApiResponse($data,$statusCode);
    }

    public function getMetaData($user)
    {
      $loggedInUser = Api::getAuthenticatedUser();
        $userObj = User::find($user);
        $statusCode = 200;
        $data['data'] = [];
        if(!$userObj){
          $statusCode = 422;
          $data['data']['error'] = "User Not Found";
        }else{
          $shipmentObjet = [];
          $shipment = Shipment::select(DB::raw('count(id) as counter'),'status')
                      ->where('user_id',$user)
                      ->groupBy('status')
                      ->pluck('counter','status');

          $shipmentObjet = [];
          foreach(config('site.shipment_statuses') as $key => $value){
            $shipmentObjet[$value] = isset($shipment[$key]) ? $shipment[$key] : 0;
          }

          $quotationObjet = [];
          $quotation = Quotation::select(DB::raw('count(id) as counter'),'status')
                      ->where('user_id',$user)
                      ->groupBy('status')
                      ->get()
                      ->pluck('counter','status');

          $quotationObjet = [];
          foreach(config('site.quotation_statuses') as $key => $value){
            $quotationObjet[$value] = isset($quotation[$key]) ? $quotation[$key] : 0;
          }

          $data['data']['shipment'] = [
              'total' => Shipment::where('user_id',$user)->count(),
              'counter' => $shipmentObjet
          ];
          $data['data']['quotation'] = [
              'total' => Quotation::where('user_id',$user)->count(),
              'counter' => $quotationObjet
          ];
          $data['data']['invoice'] = [
              'total' => Invoice::where('user_id',$user)->count(),
          ];
        }
        return Api::ApiResponse($data,$statusCode);
    }

    /**
    	 *  Update User
    	 *
    	 * This endpoint is use for update User Detail
    	 */
    public function update($user,UpdateRequest $request)
    {
        $userObj = User::find($user);
        $userObj->update(request()->all());
        if($request->role_id){
          $role = Role::whereIn('id',$request->role_id)->pluck('name');
          $userObj->syncRoles($role);
        }
        if($request->twofa == "0"){
			$userObj->update([
                'google2fa_secret' => null
            ]);
        }
        $user = User::find($user)->toArray();
		$user['image_name'] = $user['image_name'] != NULL ? asset($user['image_path'].$user['image_name']) : NULL;
		$data['data']['user'] = $user;
        return Api::ApiResponse($data);
    }

    /**
    	 *  Get List Of User
    	 *
    	 */
    public function getList()
    {
        $data['data'] =  User::select(DB::raw('concat(first_name," ",last_name," - ",customer_code) as name'),'id')
          ->where('first_name','like','%'.request('q').'%')
              ->orWhere('last_name','like','%'.request('q').'%')
              ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.request('q').'%')
              ->get();
        if(!request('q')){
          $data['data'] = [];
        }
        return Api::ApiResponse($data);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $loggedInUser = Api::getAuthenticatedUser();
        if (!(Hash::check($request->get('current_password'), $loggedInUser['user']->password))) {
          $statusCode = 422;
          $data['data']['error'] = "Your current password does not matches with the password you provided. Please try again.";
          return Api::ApiResponse($data,$statusCode);
        }
        if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
             $statusCode = 422;
             $data['data']['error'] = "New Password cannot be same as your current password. Please choose a different password.";
             return Api::ApiResponse($data,$statusCode);
         }
        $loggedInUser['user']->password = request('new_password');
        $loggedInUser['user']->save();
        $data['data'] =  $loggedInUser['user']->toArray();
        return Api::ApiResponse($data);
    }

    public function readNotification()
    {
        $data['data'] = [];
        if(request('notification_id')){
          auth()->user()->notifications()->where('id', '=', request('notification_id'))->update([
            'read_at' => date_time_database('now')
          ]);
        }else{
          auth()->user()->notifications()->update([
            'read_at' => date_time_database('now')
          ]);
        }
        return Api::ApiResponse($data);
    }

    public function delete($id)
    {
		$user = User::find($id);
		// if($user->plan_type=="paid"){
		// 	$statusCode = 422;
		//  	$data['data']['error'] = "Oops..! You can not delete this customer as the customer has subscribed for Paid Membership on GlobalParcelForward.";
		// }else{
			$loggedInUser = Api::getAuthenticatedUser();
			$statusCode = 200;
			$data['data'] = [];
			if($user){
				$name = $user['first_name']." ".$user['last_name'];
				$code = $user['customer_code'];
				$date = date('Y-m-d H:i:s');

				$quotation = Quotation::where('user_id',$id)->pluck('id');
				QuotationItem::whereIn('quotation_id',$quotation)->update(['deleted_at'=>$date]);
				Quotation::where('user_id',$id)->delete();

				$shipment = Shipment::where('user_id',$id)->pluck('id');
				ShipmentItem::whereIn('shipment_id',$shipment)->update(['deleted_at'=>$date]);
				ShipmentHistory::whereIn('shipment_id',$shipment)->update(['deleted_at'=>$date]);
				Shipment::where('user_id',$id)->delete();

				UserSecurityQuestion::where('user_id',$id)->delete();
				UserMobile::where('user_id',$id)->delete();
				ContactUs::where('user_id',$id)->delete();
				Invoice::where('user_id',$id)->delete();
				Log::where('user_id',$id)->delete();
				UserMail::where('user_id',$id)->delete();
				Otp::where('user_id',$id)->delete();
				PhotoRequest::where('user_id',$id)->delete();
				Payment::where('user_id',$id)->delete();
				$roles = $user->getRoleNames()->toArray();
				foreach($roles as $key => $value){
					$user->removeRole($value);
				}

				$user->delete();
        $data['data']['success'] = "User deleted successfully but you have to unsubscribe this user's membership as the customer has subscribed for Paid Membership on GlobalParcelForward.";
				$userName = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
				addLog($loggedInUser['user']['id'],'User Delete','<b>'.$userName."</b> has deleted user <b>".$name ." [". $code."].</b>");
			}else{
				$statusCode = 422;
				$data['data']['error'] = "User Not Found";
			}
		//}
		return Api::ApiResponse($data, $statusCode);

    }

}
