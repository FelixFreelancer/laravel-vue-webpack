<?php
use App\Models\Setting;

	function getBusinessDays($startDate, $endDate)
	{
	    $begin = strtotime($startDate);
	    $end   = strtotime($endDate);
	    if ($begin > $end) {
	        return 0;
	    } else {
	        $no_days  = 0;
	        while ($begin <= $end) {
	            $what_day = date("N", $begin);
	            if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
	                $no_days++;
	            $begin += 86400;
	        };
	        return $no_days;
	    }
	}

	function getMembershipAmount(){
		return Setting::where('name','subscription_price')->pluck('value')->first();
	}

	function getPaymentDetail(){
		$buttonId = Setting::where('name','payPal_button_id')->pluck('value')->first();
		$alias = Setting::where('name','payPal_alias')->pluck('value')->first();
		return [
			'epdq' => [
	          'pspid' => env('EPDQ_PSPID'),
	          'mode' => env('EPDQ_MODE'),
	          'test' => env('EPDQ_TEST_URL'),
	          'prod' => env('EPDQ_PROD_URL'),
	          // 'user_id' => env('EPDQ_USER_ID'),
	          'sha_phrase' => env('EPDG_SHAPHRASE'),
	          'sha' => env('EPDQ_SHA'),
	        ],
	        'paypal' => [
	          'url' => env('PAYPAL_SUBSCRIPTION_URL'),
	          'unsubscribe_url' => env('PAYPAL_SUBSCRIPTION_URL').'?cmd=_subscr-find&alias='.$alias.'&switch_classic=true',
	          'button' => $buttonId
	        ]
		];
	}

	function appendCurrency($amount){
		return $amount != NULL ? config('site.default_currency').$amount : NULL;
	}

	function getUserStatus($userObj){
		if($userObj->email_verified_at == NULL && $userObj->cd_phone_verified_at == NULL){
			return 'Non-Activated';
		}else if($userObj->email_verified_at != NULL && $userObj->cd_phone_verified_at == NULL){
			return 'Pre-Activated';
		}else{
			return 'Activated';
		}
	}

    function getPaginationObject($total,$start,$count)
    {
      $next = $start + $count;
      $last = $start - 1;
      $end  = ($start + $count)-1;
      $data['total'] = $total;
      $data['start'] = $start;
      $data['end'] = $end >= $total ? $total : $end;
      $data['next_id'] = $next < $total ? $next : NULL;
      $data['last_id'] = $last > 0 ? $last : NULL;
      return $data;
    }

	function getUserVerificationStatus($userObj){
		if($userObj->email_verified_at == NULL && $userObj->cd_phone_verified_at == NULL){
			return false;
		}else if($userObj->email_verified_at != NULL && $userObj->cd_phone_verified_at == NULL){
			return false;
		}else{
			return true;
		}
	}

	function loadUserCssFile() {

        $path = base_path() . "/user/manifest.json";
        $cssFile = 'style.css';
        if ( \File::exists($path) ) {

            $file = \File::get($path); // string
            $fileAry = json_decode($file , true );
            $cssFile = $fileAry['user.css'];
        }

        return $cssFile;
    }

	function loadUserJsFile() {

        $path = base_path() . "/user/manifest.json";
        $jsFile = 'app.js';
        if ( \File::exists($path) ) {

            $file = \File::get($path); // string
            $fileAry = json_decode($file , true );
            $jsFile = $fileAry['user.js'];
        }

        return $jsFile;
    }
