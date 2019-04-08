<?php

namespace App\Http\Controllers;

use App\Models\Shipment;

class ShipmentPaymentController extends Controller
{
    public function index(Shipment $shipment)
    {
        $data = [];

        $data['id'] = $shipment['id'];
        $data['user'] = auth()->id();
        $data['payment_type'] = '1';

        return view('frontend.pages.users.general-payment', $data);
    }
}
