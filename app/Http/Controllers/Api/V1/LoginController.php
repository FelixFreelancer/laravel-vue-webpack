<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TwofaCheckRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Library\Api;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @resource User
 *
 */

class LoginController extends Controller
{
    /**
    * Login
    *
    * This endpoint is for login
    */
    public function index(LoginRequest $request)
    {

        $credentials = request()->only('email', 'password');

        $userObj = User::where('email', '=', request('email'))
            ->first();
        if($userObj && $userObj->google2fa_secret != null){
          $data['data']['user_id'] =  $userObj->id;
          $data['data']['authy_required'] = true ;
          $statusCode =  200;
          return Api::ApiResponse($data, $statusCode);
        }
        if($userObj && $userObj->is_active == 0){
		  $data['data'] = [
			'error' => 'You are no longer to access your account.Please <a href="'.url('contact-us').'">contact a support representative here</a>'
		  ];
		  $statusCode =  401;
		  return Api::ApiResponse($data, $statusCode);
        }
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $data['data'] = [
                  'error' => 'Invalid Credentials'
                ];
                $statusCode =  401;
                return Api::ApiResponse($data, $statusCode);
            }
        } catch (JWTException $e) {
            $data['data'] = [
              'error' => 'Could Not Create Token'
            ];
            $statusCode =  500;
            return Api::ApiResponse($data, $statusCode);
        }
        $user = JWTAuth::toUser($token);

        $user['image_name'] = $user['image_name'] != NULL ? asset($user['image_path'].$user['image_name']) : NULL;
        $data['data']['user'] =  $user;
        $data['data']['role'] =  $user->getRoleNames();
        $data['data']['permissions'] =  $user->getPermissionsViaRoles()->pluck('name');
        $data['data']['unread_notifications'] =   $user->unreadNotifications()->whereIn('type',['App\Notifications\QuotationCreated','App\Notifications\RequestShipmentItemPhoto'])->count();
        unset($user['roles']);
        $data['data']['token'] =  $token;
        $data['data']['shipment_statuses'] =  config('site.shipment_statuses');
        $data['data']['authy_required'] = $user->google2fa_secret != null ? true : false;
        $statusCode =  200;
        return Api::ApiResponse($data, $statusCode);
    }


    public function googleAuthenticationStore(TwofaCheckRequest $request)
    {

        $data = [];

        $user = User::find(request('user_id'));

        $auth_code = implode('', request('auth_code'));

        $google2fa = app('pragmarx.google2fa');

        $verification = $google2fa->verifyGoogle2FA($user->google2fa_secret, $auth_code);

        if ($verification) {
			$data['status'] = true;
            $data['data']['user'] =  $user;
            $data['data']['role'] =  $user->getRoleNames();
            $data['data']['permissions'] =  $user->getPermissionsViaRoles()->pluck('name');
            unset($user['roles']);
            $data['data']['token'] =  JWTAuth::fromUser($user);
            $data['data']['shipment_statuses'] =  config('site.shipment_statuses');
            $data['data']['authy_required'] = $user->google2fa_secret != null ? true : false;
            $statusCode =  200;
        } else {
			$statusCode =  422;
          	$data['data']['error'] = 'Please check entered authentication code.';
        }
		return Api::ApiResponse($data, $statusCode);
    }
}
