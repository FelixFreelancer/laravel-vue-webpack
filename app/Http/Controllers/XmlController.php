<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;

class XmlController extends Controller
{
    public function index(User $user)
    {
        $otp = Otp::where('user_id', '=', $user->id)->first();
        //dd($otp->otp);
        $response = twilioTwiML();
		$otpVal = str_split($otp->otp);
        $response->say('Hello, thank you for using our phone verification system. Your seven digit code for Global parcel forward is ', ['voice'=>'alice']);
		foreach($otpVal as $key => $value){
			$response->say($value, ['voice'=>'alice']);
			$response->pause(['length' => 1]);
		}
		$response->say(' again: ');
		foreach($otpVal as $key => $value){
			$response->say($value, ['voice'=>'alice']);
			$response->pause(['length' => 1]);
		}
		$response->say('. Good bye.', ['voice'=>'alice']);
        return $response;
    }
}
