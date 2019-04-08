<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller
{
    public function index()
    {
        if (request('payment_gateway_type') == 3) {
            return redirect()->route('paypal.express-checkout', 'shipment_id=' . request('shipment_id'));
        }
        $data = [];
        $data['user_id'] = request('user_id');
        $data['payment_type'] = request('payment_type');
        return view('frontend.pages.payment-gateway', $data);
    }

    public function store()
    {
        $this->validate(request(), [
            'payment_type' => 'required|numeric',
            'status'       => 'required|numeric',
            'user_id'      => 'required|numeric',
        ]);
        $inputs = request()->only('payment_type', 'status', 'user_id');
        $inputs['payment_gateway_type'] = 1;
        $inputs['amount'] = getMembershipAmount();
        Payment::create($inputs);
        if (request('status') == 1) {
            User::where('id', '=', request('user_id'))
                ->update([
                    'plan_type'           => 'paid',
                    'membership_validity' => date_time_database('+1 month'),
                    'started_at'          => date_time_database('now'),
                    'auto_renew'          => 1
                ]);
            session()->flash('message', 'Your account membership payment is successful.');
            session()->flash('class', 'success');
            return redirect()->route('login.index');
        } elseif (request('status') == 2) {
            session()->flash('message', 'Payment is declined from payment gateway please try again.');
            session()->flash('class', 'danger');
            return redirect()->route('users.payment', request('user_id'));
        }
    }
}
