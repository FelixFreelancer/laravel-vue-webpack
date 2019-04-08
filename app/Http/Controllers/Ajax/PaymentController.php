<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\PaymentRequest;
use App\Http\Requests\Front\PaymentPageRequest;
use App\Http\Requests\Front\User\DowngradeRequest;
use App\Http\Requests\Front\AutoRenewRequest;
use App\Models\Shipment;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Library\Api;
use Srmklive\PayPal\Services\ExpressCheckout;
use DB;


class PaymentController extends Controller
{
    protected $epdqObject, $paymentObject, $provider;


    public function __construct()
    {
		$paymentDetail = getPaymentDetail();
        $this->epdqObject = $paymentDetail['epdq'];
        $this->paymentObject = $paymentDetail['paypal'];
        $this->provider = new ExpressCheckout();
    }
    //
    // public function autorenew(AutoRenewRequest $request)
    // {
    //     $payment = Payment::find($request->payment_id);
	// 	$user = User::find($payment['user_id']);
	// 	if($payment['payment_gateway_type'] == 1){
	// 		if($request['autorenew'] == 1){
	// 			session()->put('autorenew',1);
	// 			\Log::info("======== Paypal payment gateway autorenew enable =========");
	// 		}else{
	// 			session()->put('autorenew',0);
    //     return redirect($this->paymentObject['unsubscribe_url']);
	// 		}
	// 		$data['url'] = $this->paymentObject['url'];
	// 		$data['user'] = $payment['user_id'];
	// 		$data['autorenew'] = $request['autorenew'];
	// 		return view('frontend.pages.paypal',$data);
	// 	}else{
	// 		$data['action'] = $this->epdqObject['mode'] == 'test' ? $this->epdqObject['test'] : $this->epdqObject['prod'];
	// 		$payment['amount'] = 100;
	// 		$data['formParameter'] = $this->getCartEpdq(true,$payment,$user);
	// 		\Log::info("======== ePDQ payment gateway upgrade =========");
	// 		\Log::info(json_encode($data['formParameter']));
	// 		\Log::info("========================================");
	// 	}
	// 	return view('frontend.pages.epdq',$data);
    // }

    /*
    ** EPDQ PAYMENT
    */

    public function payViaEpdq(PaymentPageRequest $request)
    {
      $recurring = $request['recurring'] ? true : false;
      $entity = [];
      $input = [
        'user_id' =>  request('user_id'),
        'payment_entity' => $recurring ? 'subscription' : 'order',
        'status' => 1,
        'payment_gateway_type' =>  2,
      ];
      if($recurring){
        $input += [
          'amount' => getMembershipAmount(),
          'payment_type' => 3,
        ];
      }else{
        if($request['payment_type'] == 1){
          $entity = Shipment::find($request['id']);
        }else if($request['payment_type'] == 2){
          $entity = Quotation::find($request['id']);
        }
        $input += [
          'payment_type' => $request['payment_type'],
          'amount' => sprintf('%.2f',$entity['total']),
          'entity_id' => $request['id'],
        ];
      }
      $payment = Payment::create($input);
      $user = User::find(request('user_id'));
      $data['action'] = $this->epdqObject['mode'] == 'test' ? $this->epdqObject['test'] : $this->epdqObject['prod'];

      $data['formParameter'] = $this->getCartEpdq($recurring,$payment,$user,false);
      \Log::info("======== ePDQ payment gateway =========");
      \Log::info(json_encode($data['formParameter']));
      \Log::info("========================================");
       //return $data;
      return view('frontend.pages.epdq',$data);
    }

    private function getCartEpdq($recurring, $payment,$user,$isActive=true)
    {
          $data = [
            'ACCEPTURL' => url('payment/epdq-response'),
            'AMOUNT' => $payment['amount'] * 100,
            'CN' => $user['first_name']." ".$user['last_name'],
            'CURRENCY' => 'GBP',
            'ORDERID' => $payment['id'],
            'PSPID' => $this->epdqObject['pspid'],
          ];
        if ($recurring) {
          $data += [
            'SUB_AMOUNT' => $payment['amount'] * 100,
            'SUB_ENDDATE' => date('Y-m-d', strtotime(' +'.config('site.membership_validity').' months')),
            'SUB_ORDERID' => $payment['id'],
            'SUB_PERIOD_UNIT' => "m",
            'SUB_PERIOD_NUMBER' => "1",
            'SUB_PERIOD_MOMENT' => date('d'),
            'SUB_STARTDATE' => date('Y-m-d'),
            'SUBSCRIPTION_ID' => $payment['id'],
          ];
		  if($isActive){
			  $data += [
				  'SUB_STATUS' => 1,
			  ];
		  }else {
			  $data += [
				  'SUB_STATUS' => 0,
			  ];
		  }
        }
        ksort($data);
        $data['SHASIGN'] = $this->generateSignature($data);

        return $data;
    }


    function generateSignature($data){
    		$shastr = '';
        foreach($data as $key => $value){
          $shastr .= $key.'='.$value.$this->epdqObject['sha_phrase'];
        }

        return hash($this->epdqObject['sha'],$shastr);
  	}

    function handleEpdqResponse(){
      \Log::info("======== ePDQ payment handle =========");
      \Log::info(json_encode(request()->all()));
      \Log::info("========================================");
        $payment = Payment::find(request('orderID'));
        $payment->transaction_id = request('PAYID');
        $payment->payment_gateway_response = json_encode(request()->all());
        $payment->payment_gateway_status = request('status');
        $payment->status = 3;
        $payment->save();

        $user = User::find($payment->user_id);

        if($payment->payment_entity == "subscription"){
          $user->plan_type = "paid";
          $user->started_at = date('Y-m-d H:i:s');
          $user->membership_validity = date('Y-m-d H:i:s',strtotime('+'.config('site.membership_validity').' months'));
          $user->save();

          $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
          ->where(DB::raw('year(created_at)'), '=', date('Y'))
          ->count();
          $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
          $invoiceObj = Invoice::create([
            'entity_type' => 3,
            'user_id' => $payment->user_id,
            'total' => $payment->amount,
            'invoice_number' => $invoiceNumber
          ]);

          $invoice = generateMembershipInvoice($invoiceObj['id']);
          $invoiceObj->invoice_name = $invoice['invoice_name'];
          $invoiceObj->invoice_path = $invoice['invoice_path'];
          $invoiceObj->save();
		  if(session()->has('upgrade') || session()->has('downgrade')){
		  	 session()->forget('upgrade');
		  	 session()->forget('downgrade');
		  	return redirect('/dashboard/#/subscriptions-plan');
		  }
          return view('frontend.pages.login.index');
        }

        if($payment['payment_type'] == 1){
          $shipment=Shipment::where('id','=',$payment['entity_id'])->update([
              'status'=>'3',
          ]);
          addShipmentStatus($payment['entity_id'], 3 ,$user['id'] );
		  session()->put('payment',1);
          return redirect('/dashboard/#/ready-for-shipping');
        }
        if($payment['payment_type'] == 2){
          $quotation=Quotation::where('id','=',$payment['entity_id'])->update([
              'status'=>'3',
          ]);
          addShipmentStatus($payment['entity_id'], 3 ,$user['id'] );

          $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
          ->where(DB::raw('year(created_at)'), '=', date('Y'))
          ->count();
          $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
          $invoiceObj = Invoice::create([
            'entity_type' => 2,
            'total' => $payment['amount'],
            'entity_id' => $payment['entity_id'],
            'user_id' => $user['id'],
            'invoice_number' => $invoiceNumber,
            'due_date' => date('Y-m-d', strtotime(' +1 day'))

          ]);
          $invoice = generatePersonalShopperInvoice($payment['entity_id']);
          $invoiceObj->invoice_name = $invoice['invoice_name'];
          $invoiceObj->invoice_path = $invoice['invoice_path'];
          $invoiceObj->save();
		  session()->put('payment',1);
          return redirect('/dashboard/#/personal-shopper');
        }
    }

    /*
    ** PAYPAL PAYMENT
    */

    public function payViaPaypal(PaymentPageRequest $request)
    {
      $recurring = $request['recurring'] ? true : false;

      $payment = Payment::create([
          'user_id'   => request('user_id'),
          'payment_gateway_type' => 1,
          'payment_type' => $request['payment_type'],
          'entity_id' => $request['id'],
          'status' => 1,
      ]);

      $cart = $this->getCart($recurring, $payment['id'], $request['payment_type'], $request['id']);
      $payment->amount=  $cart['total'];
      $payment->save();

      session()->put('payment_type', $request['payment_type']);
      session()->put('entity_id', $request['id']);

      try {
          $response = $this->provider->setExpressCheckout($cart, $recurring);
          if (!$response['paypal_link']) {
            $payment->status=2;
            $payment->save();
            $statusCode = 422;
            $data['data']['error'] = isset($response['L_LONGMESSAGE0']) ? $response['L_LONGMESSAGE0'] : 'Something went wrong.';
            return Api::ApiResponse($data,$statusCode);
          }
      } catch (\Exception $e) {
        $payment->status=2;
        $payment->save();
        $statusCode = 422;
        $data['data']['error'] = 'Error processing PayPal payment for Order '.$payment['id'];
        return Api::ApiResponse($data,$statusCode);
      }
      return redirect($response['paypal_link']);
    }

    private function getCart($recurring, $payment_id, $payment_type, $entity_id)
    {
        $data = [];
        $amount = 0;
        $entity = [];
        if($payment_type == 1){
          $entity = Shipment::find($entity_id);
          $name = $entity['name'];
          $desc = "Shipment order for ".$entity['name'];
        }else if($payment_type == 2){
          $entity = Quotation::find($entity_id);
          $name = $entity['quotation_number'];
          $desc = "Quotation order for ".$entity['quotation_number'];
        }else{
          $name = 'Monthly Subscription #'.$payment_id;
          $desc = 'Monthly Subscription #'.$payment_id;
        }

        if ($recurring) {

            return [
                'items' => [
                    [
                        'name' => $name,
                        'price' => sprintf("%.2f",$entity['total']),
                        'qty' => 1,
                    ],
                ],

                'return_url'          => url('payment/paypal-response?recurring=1'),
                'subscription_desc'   => $desc,
                'invoice_id'          => config('paypal.invoice_prefix') . '_' . $payment_id,
                'invoice_description' => "Order #" . $payment_id . " Invoice",
                'cancel_url'          => url('/payment/paypal-cancel'),
                'total'               => sprintf("%.2f",$entity['total']),
                'currency_code'       => 'GBP',
            ];
        }

        return[
            'items' => [
                [
                    'name'  => $name,
                    'price' => sprintf("%.2f",$entity['total']),
                    'qty'   => 1,
                ],
            ],
            'return_url'          => url('payment/paypal-response'),
            'subscription_desc'   => $desc,
            'invoice_id'          => config('paypal.invoice_prefix') . '_' . $payment_id,
            'invoice_description' => "Order #" . $payment_id . " Invoice",
            'cancel_url'          => url('/payment/paypal-cancel'),
            'total'               => sprintf("%.2f",$entity['total']),
            'currency_code'       => 'GBP'
        ];
    }

    public function handlePaypalResponse(Request $request)
    {
        $recurring = $request->input('recurring', false) ? true : false;

        $token = $request->get('token');

        $PayerID = $request->get('PayerID');

        $response = $this->provider->getExpressCheckoutDetails($token);
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
          $statusCode = 422;
          $data['data']['error'] = 'Error processing PayPal payment';
          return Api::ApiResponse($data,$statusCode);
        }

        $payment_id = explode('_', $response['INVNUM'])[1];

        $payment_type=session()->pull('payment_type');
        $entity_id=session()->pull('entity_id');
        $cart = $this->getCart($recurring, $payment_id, $payment_type,$entity_id);
        // if ($recurring === true) {
        //
        //
        //   $startdate = Carbon::now()->toAtomString();
        //   $profile_desc = !empty($cart['subscription_desc']) ? $cart['subscription_desc'] : $cart['invoice_description'];
        //   $data = [
        //     'PROFILESTARTDATE' => $startdate,
        //     'DESC' => $profile_desc,
        //     'BILLINGPERIOD' => 'Day', // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
        //     'BILLINGFREQUENCY' => 1, //
        //     'AMT' => $cart['total'], // Billing amount for each billing cycle
        //     'CURRENCYCODE' => 'USD', // Currency code
        //   ];
        //
        //   $response = $provider->createRecurringPaymentsProfile($data, $token);
        //     // if recurring then we need to create the subscription
        //     // you can create monthly or yearly subscriptions
        //     return $response;
        //     $response = $this->provider->createMonthlySubscription($response['TOKEN'], $response['AMT'], $cart['subscription_desc']);
        //     $status = 'Invalid';
        //     // if after creating the subscription paypal responds with activeprofile or pendingprofile
        //     // we are good to go and we can set the status to Processed, else status stays Invalid
        //     if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], [
        //             'ActiveProfile',
        //             'PendingProfile'
        //         ])) {
        //         $status = 'Processed';
        //     }
        // } else {
            $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];

        // }

        $payment = Payment::find($payment_id);

        $payment->payment_gateway_status = $status;

        if(isset($payment_status)){
          $payment->payment_gateway_response = json_encode($payment_status);
          $payment->transaction_id = $payment_status['PAYMENTINFO_0_TRANSACTIONID'];
          $payment->seller_account_id = $payment_status['PAYMENTINFO_0_SELLERPAYPALACCOUNTID'];
          $payment->merchant_account_id = $payment_status['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID'];
        }

        // if payment is recurring lets set a recurring id for latter use
        // if ($recurring === true) {
        //     $invoice->recurring_id = $response['PROFILEID'];
        // }
        $payment->status = 3;

        $payment->save();
        // if($recurring){
        //   $user = User::find($payment->user_id);
        //   $user->started_at = date('Y-m-d H:i:s');
        //   $user->membership_validity = date('Y-m-d H:i:s',strtotime('+'.config('site.membership_validity').' months'));
        //   $user->save();
        //
        //   $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
        //   ->where(DB::raw('year(created_at)'), '=', date('Y'))
        //   ->count();
        //   $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
        //   $invoiceObj = Invoice::create([
        //     'entity_type' => 3,
        //     'user_id' => $payment->user_id,
        //     'total' => $payment->amount,
        //     'invoice_number' => $invoiceNumber
        //   ]);
        //
        //   $invoice = generateMembershipInvoice($invoiceObj['id']);
        //   $invoiceObj->invoice_name = $invoice['invoice_name'];
        //   $invoiceObj->invoice_path = $invoice['invoice_path'];
        //   $invoiceObj->save();
        //   return view('frontend.pages.login.index');
        // }
        // App\Invoice has a paid attribute that returns true or false based on payment status
        // so if paid is false return with error, else return with success message
      //  if ($invoice->payment_gateway_status == "Success") {
            if($payment_type == 1){

                $shipment=Shipment::where('id','=',$entity_id)->update([
                    'status'=>'3',
                ]);
                addShipmentStatus($entity_id, 3 ,$payment->user_id );
				session()->put('payment',1);
                return redirect('/dashboard/#/ready-for-shipping');
            }

            if($payment_type == 2){
                $quotation=Quotation::where('id','=',$entity_id)->update([
                    'status'=>'3',
                ]);
                addShipmentStatus($entity_id, 3 ,$payment->user_id );

                $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
                ->where(DB::raw('year(created_at)'), '=', date('Y'))
                ->count();
                $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
                $invoiceObj = Invoice::create([
                  'entity_type' => 2,
                  'entity_id' => $entity_id,
                  'user_id' => $payment->user_id,
                  'total' => $payment->amount,
                  'invoice_number' => $invoiceNumber,
                  'due_date' => date('Y-m-d', strtotime(' +1 day'))
                ]);

                $invoice = generatePersonalShopperInvoice($entity_id);
                $invoiceObj->invoice_name = $invoice['invoice_name'];
                $invoiceObj->invoice_path = $invoice['invoice_path'];
                $invoiceObj->save();
				session()->put('payment',1);
                return redirect('/dashboard/#/personal-shopper');
            }

        $statusCode = 422;
        $data['data']['error'] = 'Error processing PayPal payment for Order '.$payment_id;
        return Api::ApiResponse($data,$statusCode);

    }

    public function handlePaypalSubscriptionResponse()
    {
      \Log::info("====== Paypal subscription called : ".date('d-m-Y H:i:s')."=====");
      \Log::info(request()->all());
      \Log::info("==============================");
	  if(session()->has('autorenew')){
		  \Log::info("Session autorenew ".session()->get('autorenew'));
		  $user = User::find(request('cm'));
		  $user->auto_renew = session()->get('autorenew');
		  $user->save();
	  }
	  if(session()->has('upgrade') || session()->has('downgrade')){
		   session()->forget('upgrade');
		   session()->forget('downgrade');
		 return redirect('/dashboard/#/subscriptions-plan');
	  }
        return view('frontend.pages.login.index');

    }


    public function paypalNotify(){

		\Log::info("====== Notify URL called : ".date('d-m-Y H:i:s')."=====");
		\Log::info(json_encode(request()->all()));
		if(request('txn_type') == 'subscr_cancel' || request('txn_type') == 'subscr_eot'){
			$user = User::find(request('custom'));
			$user->plan_type="free";
			$user->auto_renew="0";
			$user->save();
		}else{
      if(request('mc_gross') > 0)
      {
    			$payment = Payment::create([
    				'user_id'   => request('custom'),
    				'payment_gateway_type' => 1,
    				'payment_type' => 3,
    				'status' => 3,
    				'payment_gateway_status' => request('payment_status'),
    				'payment_gateway_response' => json_encode(request()->all()),
    				'transaction_id' => request('txn_id'),
    				'amount' => request('mc_gross'),
    				'payment_entity' => 'subscription',
    			]);
    			$user = User::find(request('custom'));
    			$user->plan_type = "paid";
    			$user->auto_renew = 1;
    			$user->started_at = date('Y-m-d H:i:s');
    			$user->membership_validity = date('Y-m-d H:i:s',strtotime('+'.config('site.membership_validity').' months'));
    			$user->save();


            $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
            ->where(DB::raw('year(created_at)'), '=', date('Y'))
            ->count();
            $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
            $invoiceObj = Invoice::create([
              'entity_type' => 3,
              'user_id' => request('custom'),
              'total' => request('mc_gross'),
              'invoice_number' => $invoiceNumber
            ]);

            $invoice = generateMembershipInvoice($invoiceObj['id']);
            $invoiceObj->invoice_name = $invoice['invoice_name'];
            $invoiceObj->invoice_path = $invoice['invoice_path'];
            $invoiceObj->save();
      }

		}
		\Log::info(request()->all());
		\Log::info("==============================");


    }

	public function cancelPayment(){
		return view('frontend.pages.cancel-payment');
	}

	public function cancelEpdqPayment(){
		return view('frontend.pages.cancel-payment');
	}
}
