<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\GpfShipmentHandlingCharge;
class ShipmentController extends Controller
{
    public function shippingOptions()
    {
        $token = getAuthToken();
        $delievery = Country::getShortCodeByIsoCode(request('delivery'));
        $input = [
            'CollectionAddress' => [
                'Country' => request('collection'),
            ],
            'DeliveryAddress'   => [
                'Country' => isset($delievery['short_code']) ? $delievery['short_code'] :request('delivery') ,
            ],
            'Parcels'           => request('shipment')
        ];
        $result = getQuotes($token, json_encode($input));
        $rst = [];
        if (isset($result['Quotes']) && count($result['Quotes']) > 0) {
             foreach ($result['Quotes'] as $key => $value) {
                 if (isset($value['Service'])) {
                     $rst[$key] = [
                         'name'          => $value['Service']['Name'],
                         'courier_name'  => $value['Service']['CourierName'],
                         'delivery_type' => $value['Service']['DeliveryType'],
                         'logo'          => $value['Service']['Links']['Imagelarge'],
                         'features'      => $value['Service']['Features'],
                         'service'       => $value['Service']['Classification'],
                     ];
                 }
                 $rst[$key] += [
                     'estimated_delivery_date' => $value['EstimatedDeliveryDate'],
                     'price'                   => $value['TotalPrice'],
                 ];
                 $gpfChargeObject = GpfShipmentHandlingCharge::where('start_price','<=',$value['TotalPrice'])
                           ->where('end_price','>=',$value['TotalPrice']);
                 if(auth()->user() && auth()->user()->plan_type == "free" && ( auth()->user()->membership_validity == NULL || auth()->user()->membership_validity <= date('Y-m-d H:i:s'))){
                   $gpfChargeObject->where('type',1);
                 }else{
                   $gpfChargeObject->where('type',2);
                 }
                 $gpfCharge = $gpfChargeObject->first();
                 $price = isset($gpfCharge['gpf_price']) ? $gpfCharge['gpf_price'] : 0;
                 $rst[$key] += [
                   'gpf_charges' => $price,
                   'total_price' => sprintf("%.2f",$price + $value['TotalPrice']),
                 ];
             }
           }
           $data['data'] = $rst;
           return $data;
       }
}
