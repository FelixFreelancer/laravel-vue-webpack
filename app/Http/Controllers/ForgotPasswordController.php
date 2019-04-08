<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('frontend.pages.login.forgot');
    }

    public function sendResetLinkEmail()
    {
        $user = User::where('email', '=', request('email'))->first();
        if ($user != null) {
            $token = str_random(64);

            DB::table(config('auth.passwords.users.table'))
                ->where('email', '=', $user->email)
                ->where('token_type', '=', '1')
                ->delete();

            DB::table(config('auth.passwords.users.table'))->insert([
                'email'      => $user->email,
                'token'      => $token,
                'token_type' => 1,
                'created_at' => date_time_database('now')
            ]);

            try {
                Mail::to($user->email)->send(new PasswordReset($user, $token));
                session()->flash('message', 'An email has been sent to ('.request('email').') with further instructions, If it is associated with any user. Consider checking your spam folder also if you have not received it.');
                session()->flash('class', 'success');
            } catch (\Exception $e) {
                session()->flash('message', 'Something went wrong try again later.');
                session()->flash('class', 'danger');
                return back();
            }
        } else {
            session()->flash('message', 'An email has been sent to ('.request('email').') with further instructions, If it is associated with any user. Consider checking your spam folder also if you have not received it.');
            session()->flash('class', 'danger');
            return redirect()->route('login.index');
        }

        return redirect()->route('login.index');
    }
}
