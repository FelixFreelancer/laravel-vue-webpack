<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserMobile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SEO;

class RegistrationController extends Controller
{
    public function plan()
    {

		SEO::setTitle('Pricing & Membership');
		SEO::setDescription("Want to shop online but UK stores won't ship to you? Get a Globalparcelforward address and you can shop virtually any store. We'll forward your packages to you fast and for cheap!");
		SEO::opengraph()->setUrl(url('/registration/plan'));
		SEO::setCanonical(url('/registration/plan'));
        return view('frontend.pages.plan');
    }

    public function membership()
    {
        $data = [];
        $data['genders'] = collect(config('site.gender'))->map(function ($item, $key) {
            return ucwords($item);
        });
        $data['countries'] = Country::pluck('nice_name', 'iso');
        $data['country_codes'] = Country::orderBy('country_code')->pluck('country_code', 'iso');
        SEO::setTitle('Join GlobalParcelForward to Shop UK Stores & Ship Internationally');
        SEO::setDescription("Want to shop online but UK stores won't ship to you? Get a Globalparcelforward address and you can shop virtually any store. We'll forward your packages to you fast and for cheap!");
        SEO::opengraph()->setUrl(url('/registration/plan'));
        SEO::setCanonical(url('/registration/plan'));
        return view('frontend.pages.membership', $data);
    }

    public function store()
    {
        if (request('type')) {
            session(['plan_type' => request('type')]);
            return redirect()->route('registration.membership');
        } else {

            $this->validate(request(), User::validationRules(request('same_as_cd')), User::validationMessages());

            $country = Country::where('iso', '=', request('cd_country'))->first();
            $number = User::where(DB::raw('month(created_at)'), '=', date('m'))
                ->where(DB::raw('year(created_at)'), '=', date('Y'))
                ->where('cd_country', '=', $country->id)
                ->withTrashed()
				->orderBy('id','desc')
                ->first();
            $ba_country = Country::where('iso', '=', request('ba_country'))->first();
			if(isset($number['customer_number'])){
				$customer_number = $number['customer_number']+1;
			}else{
				$customer_number = date('ym') . sprintf('%03s', 1);
			}
            $user = new User(request()->all());
            $user->customer_number = $customer_number;
            $user->customer_code = "GPF-" . $customer_number . '-' . $country->iso;

            $user->is_active = 1;
            $user->cd_country = $country->id;
            $user->cd_country_code = $country->country_code;

            if ($ba_country != null) {
                $user->ba_country = $ba_country->id;
                $user->ba_country_code = $ba_country->country_code;
            }

            if (request('same_as_cd') == 1) {
                $user->same_as_cd = 1;
            } else {
                $user->same_as_cd = 0;
            }

            if (session()->has('plan_type')) {
                $user->plan_type = session('plan_type');
            } else {
                $user->plan_type = 'free';
            }
            if (!$user->save()) {
                session()->flash('message', 'Something went wrong.');
                session()->flash('class', 'danger');
                return back()->withInput();
            } else {
                $user->assignRole('Customer');
                $user->sendVerificationLink();
                //session()->flash('message', 'User created successfully.');
                //session()->flash('class', 'success');
                if ($user->plan_type == 'free') {
					$data['message'] = 'We have sent a verification email to the email address provided by you during registration. Please verify your email by clicking on the verification link in the email. Please <a  class="link-text" href="' . url()->route('users.resend.verification', $user->id) . '">click here</a> to resend if you haven\'t received the email.';
                    $data['popup'] = true;
                    return view('frontend.pages.login.index', $data);
                } elseif ($user->plan_type == 'paid') {
                    return redirect()->route('users.payment', $user->id);
                }
            }
        }
    }

    public function updateNumber()
    {
        $data = [];
        $rules = [
          'user_id' => 'required',
          'cd_country_code' => 'required|exists:countries,id',
        ];
        if (request('cd_phone') && request('cd_country_code') && request('user_id')) {
            $country = Country::where('id', '=', request('cd_country_code'))->first();
            $rules += [
                'cd_phone' => 'required|unique:users,cd_phone,' . request('user_id')
            ];
        }
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            $data['status'] = false;
            $data['errors'] = $validator->errors();
        } else {
            $user = User::find(request('user_id'));
            $user->cd_country_code = $country->country_code;
            $user->cd_phone = request('cd_phone');
            $user->save();
              UserMobile::where('user_id', '=', $user->id)->delete();
            UserMobile::create([
                'new_mobile' => request('cd_phone'),
                'user_id' => request('user_id')
                ]);
            $data['user'] = $user;
            $data['status'] = true;
        }
        return $data;

    }

    public function unique()
    {
        $data = [];
        $rules = [
            'email' => 'nullable|unique:users,email,NULL,id,deleted_at,NULL',
        ];
        if (request('cd_phone') && request('country_code')) {
            $country = Country::where('iso', '=', request('country_code'))->first();
            $rules += [
                'cd_phone' => 'nullable|unique:users,cd_phone,NULL,id,cd_country_code,' . $country->id
            ];
        }
        $messages = [
            'email.unique'    => 'This email address is not available.',
            'cd_phone.unique' => 'This phone number is not available.'
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            $data['status'] = false;
            $data['errors'] = $validator->errors();
        } else {
            $data['status'] = true;
        }
        return $data;
    }

    public function verification($token)
    {
        $email_token = DB::table('password_resets')
            ->where('token', '=', $token)
            ->where('token_type', '=', '2')
            ->first();
        if ($email_token != null) {
            $data = [];
            $user = User::where('email', '=', $email_token->email)->first();
            if($user == NULL){
              $data['status'] = false;
              $data['message'] = 'Something went wrong.Please try again later.';
            }else{

              if($user->email_verified_at != NULL)
              {
                return redirect()->route('login.index');
              }
              else
              {
              if ($email_token->created_at >= date_time_database('-2 hour')) {
                $user->update([
                  'email_verified_at' => date_time_database('now')
                ]);
                $data['message'] = 'Thank you for verifying your email address. Please login to complete registration and
                activate your account.';
                $data['link'] = url()->route('login.index');
                $data['button'] = "Login Now";
                $data['status'] = true;
              } else {
                $data['status'] = false;
                $data['message'] = 'Your token is expired. Please resend verification mail.';
                $data['link'] = url()->route('users.resend.verification',$user->id);
                $data['button'] = "Resend Now";
                $data['expired'] = true;
              }
                $data['popup'] = true;

                return view('frontend.pages.login.index', $data);

             }
            }
        } else {
            return redirect()->route('login.index');
        }
    }

    public function verificationStatus(User $user)
    {
        $data = [];
        $data['status'] = true;
        $data['message'] = '';
        $data['title'] = 'User Verification';
        if ($user->email_verified_at == null) {
            $data['message'] .= 'Your email is not verified yet. Please check your email inbox for verification mail. If you didn\'t get it Please <a href="' . url()->route('users.resend.verification', $user->id) . '">click here</a> for resend verification mail. <br> <br>';
            $data['status'] = false;
        }
        // if ($user->cd_phone_verified_at == null) {
        //     $data['message'] .= 'Your phone is not verified yet. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> for verification.';
        //     $data['status'] = false;
        // }
        return view('frontend.pages.login.verification', $data);
    }

    public function resendVerification(User $user)
    {
        $data = [];
        $user->sendVerificationLink();
        session()->flash('message', 'Verification link is sent to your account.');
        session()->flash('class', 'danger');
        return redirect()->route('login.index');
    }

    public function otpVerification(User $user)
    {
        $data = [];
        $user->sendOtp();
        session()->flash('message', 'We have sent a seven digit code via SMS to your
registered phone number, please input the code below. It expires in <span id="otp_timer"></span>.');
        session()->flash('class', 'success');
        return redirect()->route('users.verification.otp', $user);
    }

    public function otpVerificationPage(User $user)
    {
        $data = [];
        $mobile = UserMobile::where('user_id', '=', $user->id)
                            ->orderBy('id', 'desc')
                            ->first();
        if($mobile){

            $user->cd_phone = $mobile['new_mobile'];
        }
        $data['user'] = $user;
        $data['country_codes'] = Country::orderBy('country_code')->pluck('country_code', 'id');
        return view('frontend.pages.login.otp', $data);
    }

    public function otpVerificationCheck(User $user)
    {
        $this->validate(request(), [
            'otp.*' => 'required|numeric|max:9'
        ]);
        $otp = Otp::where('user_id', '=', $user->id)
            ->where('created_at', '>=', date_time_database('-' . config('site.otp_expire') . ' min'))
            ->first();

        if ($otp == null) {
            session()->flash('message', 'Your otp is expired. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.');
            session()->flash('class', 'danger');
            return back();
        }
        if ($otp->otp != implode('', request('otp'))) {
            session()->flash('message', 'Entered otp is wrong. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.');
            session()->flash('class', 'danger');
            return back();
        }
        $user->update([
            'cd_phone_verified_at' => date_time_database('now')
        ]);
        $otp->delete();
        $mobile = UserMobile::where('user_id', '=', $user->id)
                            ->orderBy('id', 'desc')
                            ->first();
        auth()->user()->update([
            'cd_phone' => $mobile['new_mobile']
        ]);

        UserMobile::where('user_id',$user->id)
                  ->update([
                            'deleted_at'=>date_time_database('now')
                          ]);

        auth()->loginUsingId($user->id);
        auth()->user()->successfullRegistrationMail();
        auth()->user()->update([
            'last_activity' => date_time_database('now')
        ]);
        session()->put('otp_verified','true');
        return redirect()->route('users.profile.index');
    }

    public function otpCall(User $user)
    {
        $data = [];
        $client = twillioApi();
        $country = Country::find($user->cd_country);
        $country_code = 0;

        if ($country != null) {
            $country_code = '+' . $country->country_code;
        }
        $mobile = UserMobile::where('user_id', '=', $user->id)
                            ->orderBy('id', 'desc')
                            ->first();
        if($mobile)
        {
            $phone=$mobile['new_mobile'];
        }
        else
        {
            $phone=$user->cd_phone;
        }

        try{
          $call = $client->calls->create(
            $country_code . $phone, // Call this number
            '+18338125096',// From a valid Twilio numbersendMe
            [
              'url' => url('xml/' . $user->id)
            ]
          );
          $data['status'] = true;
        }catch(\Exception $e){
          $data['status'] = false;
        }
        return $data;
    }

    public function payment(User $user)
    {
        $data = [];
		if(request('upgrade')){
			session()->put('upgrade','1');
		}

        $data['user'] = $user;

        if ($user->membership_validity <= date_time_database('now') || $user->plan_type == "free") {
            return view('frontend.pages.payment', $data);
        } else {
            if (auth()->check() && auth()->user()->id == $user->id) {
                $user->update([
                    'plan_type' => 'paid'
                ]);
            }
            return back();
        }
    }
}
