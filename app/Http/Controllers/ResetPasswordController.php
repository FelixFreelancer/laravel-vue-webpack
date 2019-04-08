<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Mail\PasswordChange;
use App\Models\User;
use App\Models\Country;
use App\Models\UserSecurityQuestion;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showValidateOtpForm(Request $request, $token = null)
    {
      $email_token = DB::table('password_resets')
          ->where('token', '=', $token)
          ->where('token_type', '=', '1')
          ->first();
        if ($email_token != null) {
            $data = [];
            if ($email_token->created_at >= date_time_database('-1 hour')) {
                $user = User::where('email', '=', $email_token->email)->first();
                $user->sendOtp();
                $data['token'] = $token;
                $data['user'] = $user;
                $data['email'] = $email_token->email;
                $data['country_codes'] = Country::orderBy('country_code')->pluck('country_code', 'id');
                session()->flash('message', 'We have sent a seven digit code via SMS to your
                  registered phone number, please input the code below.It expires in <span id="otp_timer"></span>.');
                session()->flash('class', 'success');

            } else {
                $data['expired'] = true;
            }
            return view('frontend.pages.login.forgot_password_otp', $data);
           } else {
              return redirect()->route('login.index');
        }
    }

    public function validateOtp(Request $request){
      $data['status'] = true;
      $email_token = DB::table('password_resets')
          ->where('token', '=', request('token'))
          ->where('email', '=', request('email'))
          ->where('token_type', '=', '1')
          ->first();
      if ($email_token != null) {
          $user = User::where('email', '=', $email_token->email)->first();
          $otp = Otp::where('user_id', '=', $user->id)
              ->where('created_at', '>=', date_time_database('-' . config('site.otp_expire') . ' min'))
              ->first();
          if ($otp == null) {
              $data['message'] = 'Your otp is expired. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.';
              $data['class'] = 'error';
              $data['status'] = false;
          }
          else if ($otp->otp != implode('', request('otp'))) {
              $data['message'] = 'Entered otp is wrong. Please <a href="' . url()->route('users.verification.mobile', $user->id) . '">click here</a> to resend it.';
              $data['class'] = 'error';
              $data['status'] = false;
          }
        } else {
          $data['message'] = 'Something went wrong.';
          $data['class'] = 'error';
          $data['status'] = false;
        }
        return $data;
    }

    public function showResetForm(Request $request, $token = null)
    {
        $email_token = DB::table('password_resets')
            ->where('token', '=', $token)
            ->where('token_type', '=', '1')
            ->first();
        if ($email_token != null) {
            $data = [];
            if ($email_token->created_at >= date_time_database('-1 hour')) {
                $user = User::where('email', '=', $email_token->email)->first();
                $user->sendOtp();
                $data['token'] = $token;
                $data['email'] = $email_token->email;
                $data['current_question'] = UserSecurityQuestion::select('security_questions.questions')
                    ->leftJoin('security_questions', 'security_questions.id', '=', 'user_security_questions.security_question_id')
                    ->where('user_id', '=', $user->id)
                    ->first();
            } else {
                $data['expired'] = true;
            }
            return view('frontend.pages.login.reset_password', $data);
        } else {
            return redirect()->route('login.index');
        }
    }


        public function reset(Request $request)
        {
            $this->validate($request, [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
                ],
            ], [
                'password.regex' => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.',
                'password.min'   => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.'
            ]);

            $credentials = $request->only(
                'email', 'password', 'password_confirmation', 'token'
            );

            $email_token = DB::table('password_resets')
                ->where('token', '=', request('token'))
                ->where('email', '=', request('email'))
                ->where('token_type', '=', '1')
                ->first();

            if ($email_token != null) {
                $user = User::where('email', '=', $email_token->email)->first();
                $current_question = UserSecurityQuestion::where('user_id', '=', $user->id)
                    ->first();
                if ($current_question != null && strtolower($current_question->answer) != strtolower(request('answer'))) {
                    session()->flash('message', 'Given answer for security question is wrong.');
                    session()->flash('class', 'danger');
                    return redirect()->back()->withInput($request->only('email'));
                }

                $this->resetPassword($user, request('password'));
                $email_token = DB::table('password_resets')
                    ->where('token', '=', request('token'))
                    ->where('email', '=', request('email'))
                    ->where('token_type', '=', '1')
                    ->delete();
                Mail::to($user->email)->send(new PasswordChange($user));
                session()->flash('message', 'Your password successfully changed.');
                session()->flash('class', 'success');
                return redirect()->route('login.index');
            } else {
                session()->flash('message', 'Something went wrong.');
                session()->flash('class', 'danger');
                return redirect()->back()->withInput($request->only('email'));
            }
        }

    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
    }
}
