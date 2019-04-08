<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Paypal;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Shipment;
use App\Models\Invoice;
use Auth;
use DB;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }


    public function expressCheckout(Request $request)
    {
        $recurring = $request['recurring'] ? true : false;

        $invoice_id = Payment::count() + 1;

        $cart = $this->getCart($recurring, $invoice_id, $request['payment_type'], $request['entity_id']);

        Payment::create([
            'user_id'              => auth()->user()->id,
            'payment_type'         => $request['payment_type'],
            'payment_gateway_type' => 1,
            'amount'               => $cart['total'],
            'entity_id'            => $request['entity_id']
        ]);
        session()->put('payment_type', $request['payment_type']);
        session()->put('entity_id', $request['entity_id']);
        try {
            $response = $this->provider->setExpressCheckout($cart, $recurring);
            if (!$response['paypal_link']) {
                return redirect('/')->with(['code' => 'danger', 'message' => 'Something went wrong with PayPal']);
                // For the actual error message dump out $response and see what's in there
            }
        } catch (\Exception $e) {
            $invoice = $this->createInvoice($cart, 'Invalid');
            redirect('/')->with([
                'code'    => 'danger',
                'message' => "Error processing PayPal payment for Order $invoice->id!"
            ]);
        }
        return redirect($response['paypal_link']);
    }

    private function getCart($recurring, $invoice_id, $payment_type, $entity_id)
    {
        $data = [];
        $amount = 0;

        if ($payment_type == 2) {
            $data = Shipment::find($entity_id);
            if ($data == null) {
                return redirect()->route('users.warehouse');
            }
            $amount = $data['shipping_out_amount'];
        }

        if ($payment_type == 3) {
            $data = QuotationItem::where('quotation_id', '=', $entity_id)
                ->get();
            if ($data == null) {
                return redirect()->route('users.shopper.index');
            }
            foreach ($data as $key => $value) {
                $amount += ($value->quantity * $value->admin_price);
            }
        }

        if ($recurring) {
            return [
                // if payment is recurring cart needs only one item
                // with name, price and quantity
                'items' => [
                    [
                        'name' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
                        'price' => 20,
                        'qty' => 1,
                    ],
                ],

                // return url is the url where PayPal returns after user confirmed the payment
                'return_url' => url('/paypal/express-checkout-success?recurring=1'),
                'subscription_desc' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
                // every invoice id must be unique, else you'll get an error from paypal
                'invoice_id' => config('paypal.invoice_prefix') . '_' . $invoice_id,
                'invoice_description' => "Order #". $invoice_id ." Invoice",
                'cancel_url' => url('/'),
                // total is calculated by multiplying price with quantity of all cart items and then adding them up
                // in this case total is 20 because price is 20 and quantity is 1
                'total' => 20, // Total price of the cart
            ];
        }
        return [
            // if payment is recurring cart needs only one item
            // with name, price and quantity
            'items'               => [
                [
                    'name'  => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
                    'price' => $amount,
                    'qty'   => 1,
                ],
            ],

            // return url is the url where PayPal returns after user confirmed the payment
            'return_url'          => url('/paypal/express-checkout-success'),
            'subscription_desc'   => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
            // every invoice id must be unique, else you'll get an error from paypal
            'invoice_id'          => config('paypal.invoice_prefix') . '_' . $invoice_id,
            'invoice_description' => "Order #" . $invoice_id . " Invoice",
            'cancel_url'          => url('/'),
            // total is calculated by multiplying price with quantity of all cart items and then adding them up
            // in this case total is 20 because price is 20 and quantity is 1
            'total'               => $amount, // Total price of the cart
            'currency_code'       => 'EUR'
        ];
    }

    public function expressCheckoutSuccess(Request $request)
    {
        // check if payment is recurring
        $recurring = $request->input('recurring', false) ? true : false;

        $token = $request->get('token');

        $PayerID = $request->get('PayerID');
        // initaly we paypal redirects us back with a token
        // but doesn't provice us any additional data
        // so we use getExpressCheckoutDetails($token)
        // to get the payment details
        $response = $this->provider->getExpressCheckoutDetails($token);
        // if response ACK value is not SUCCESS or SUCCESSWITHWARNING
        // we return back with error
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            return redirect('/')->with(['code' => 'danger', 'message' => 'Error processing PayPal payment']);
        }

        // invoice id is stored in INVNUM
        // because we set our invoice to be xxxx_id
        // we need to explode the string and get the second element of array
        // witch will be the id of the invoice
        $invoice_id = explode('_', $response['INVNUM'])[1];

        // get cart data
        $payment_type=session()->pull('payment_type');
        $entity_id=session()->pull('entity_id');
        $cart = $this->getCart($recurring, $invoice_id, $payment_type,$entity_id);
        // check if our payment is recurring
        if ($recurring === true) {

            // if recurring then we need to create the subscription
            // you can create monthly or yearly subscriptions
            $response = $this->provider->createMonthlySubscription($response['TOKEN'], $response['AMT'], $cart['subscription_desc']);
            $status = 'Invalid';
            // if after creating the subscription paypal responds with activeprofile or pendingprofile
            // we are good to go and we can set the status to Processed, else status stays Invalid
            if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], [
                    'ActiveProfile',
                    'PendingProfile'
                ])) {
                $status = 'Processed';
            }

        } else {

            // if payment is not recurring just perform transaction on PayPal
            // and get the payment status
            $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];

        }
        // find invoice by id
        $invoice = Payment::find($invoice_id);

        // set invoice status
        $invoice->payment_gateway_status = $status;
        if(isset($payment_status)){
          $invoice->payment_gateway_response = json_encode($payment_status);
          $invoice->transaction_id = $payment_status['PAYMENTINFO_0_TRANSACTIONID'];
          $invoice->seller_account_id = $payment_status['PAYMENTINFO_0_SELLERPAYPALACCOUNTID'];
          $invoice->merchant_account_id = $payment_status['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID'];
        }

        // if payment is recurring lets set a recurring id for latter use
        // if ($recurring === true) {
        //     $invoice->recurring_id = $response['PROFILEID'];
        // }

        // save the invoice
        $invoice->save();

        // App\Invoice has a paid attribute that returns true or false based on payment status
        // so if paid is false return with error, else return with success message
      //  if ($invoice->payment_gateway_status == "Success") {
            if($payment_type==2){
                $shipment=Shipment::where('id','=',$entity_id)->update([
                    'status'=>'3',
                ]);
                addShipmentStatus($entity_id, 3 ,auth()->id() );
                return redirect()->route('users.shipments.ready-for-shipment');
            }

            if($payment_type==3){
                $quotation=Quotation::where('id','=',$entity_id)->update([
                    'status'=>'3',
                ]);
                addShipmentStatus($entity_id, 3 ,auth()->id() );

                $totalInvoice = Invoice::where(DB::raw('month(created_at)'), '=', date('m'))
                ->where(DB::raw('year(created_at)'), '=', date('Y'))
                ->count();
                $invoiceNumber = date('ymd') . sprintf('%03s', ($totalInvoice + 1));
                $invoiceObj = Invoice::create([
                  'entity_type' => 2,
                  'entity_id' => $entity_id,
                  'user_id' => auth()->id(),
                  'invoice_number' => $invoiceNumber,
                  'due_date' => date('Y-m-d', strtotime(' +1 day'))
                ]);

                $invoice = generatePersonalShopperInvoice($entity_id);
                $invoiceObj->invoice_name = $invoice['invoice_name'];
                $invoiceObj->invoice_path = $invoice['invoice_path'];
                $invoiceObj->save();

                return redirect()->route('users.personal-shopper');
            }

  //      }

        return redirect('/')->with([
            'code'    => 'danger',
            'message' => 'Error processing PayPal payment for Order ' . $invoice->id . '!'
        ]);
    }
}
