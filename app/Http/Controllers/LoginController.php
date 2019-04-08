<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SEO;

class LoginController extends Controller
{
    public function index()
    {
		SEO::setTitle('Login : Sign-in to your GPF account');
		SEO::setDescription('Signin to your Global Parcel Forward dashboard or create an account to get started.');
		SEO::opengraph()->setUrl(url('/signin'));
		SEO::setCanonical(url('/signin'));
        return view('frontend.pages.login.index');
    }

    public function store()
    {
        $this->validate(request(), User::loginValidationRules());

        $data = [];

        $user = User::where('email', '=', request('email'))
            ->first();

        if ($user == null || !Hash::check(request('password'), $user->password)) {
            $data['status'] = false;
            $data['message'] = 'Given credentials are wrong. Please enter correct credentials.';
        } else if ($user->is_active == "0") {
            $data['status'] = false;
            $data['message'] = 'You are no longer to access your account.Please <a href="'.url('contact-us').'">contact a support representative here</a>';
        } else {
            $user->update([
                'last_activity' => date_time_database('now')
            ]);
            if ($user->email_verified_at != null ) {
          //  if ($user->email_verified_at != null && $user->cd_phone_verified_at != null) {
                if ($user->plan_type == "paid"  && $user->started_at == null) {
                    $data['status'] = true;
                    $data['user_id'] = $user->id;
                    $data['url'] = url()->route('users.payment', $user->id);
                    $data['payment_required'] = true;
                }else if ($user->google2fa_secret != null) {
                    $data['status'] = true;
                    $data['payment_required'] = false;
                    $data['user_id'] = $user->id;
                    $data['authy_required'] = true;
                } else {
                    $data['status'] = true;
                    $data['authy_required'] = false;
                    $data['payment_required'] = false;
                    Auth::loginUsingId($user->id);
                    $data['url'] = url()->route('users.profile.index');
                }
            } else {
                $data['status'] = false;
                $data['message'] = "Your account is not yet activated. Please
                    <a href='" . url()->route('users.verification.status', $user->id) . "'>click here</a> to verify your email.";
                // if($user->cd_phone_verified_at==null && $user->email_verified_at!=null){
                //     $data['user_id'] = $user->id;
                //     $data['type']='otp';
                // }
            }
        }
        return $data;
    }

    public function googleAuthenticationStore()
    {
        $this->validate(request(), User::googleValidationRules());

        $data = [];

        $user = User::find(request('user_id'));

        $auth_code = implode('', request('auth_code'));

        $google2fa = app('pragmarx.google2fa');

        $verification = $google2fa->verifyGoogle2FA($user->google2fa_secret, $auth_code);

        if ($verification) {
            $data['status'] = true;
            Auth::loginUsingId($user->id);
        } else {
            $data['status'] = false;
            $data['message'] = 'Please check entered authentication code.';
        }
        return $data;
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login.index');
    }
}
