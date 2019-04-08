<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Shipment\ShippingOptionRequest;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Country;
use App\Models\GpfShipmentHandlingCharge;
use App\Library\Api;

class ShipmentController extends Controller
{
    public function shippingOptions(ShippingOptionRequest $request)
    {
        $user = auth()->user();
        $token = getAuthToken();
        $delievery = Country::find($user->cd_country);
        $shipment = Shipment::whereIn('id', $request['id'])->get();
        $rst = [];
        foreach ($shipment as $key1 => $value1) {
            $shipmentItem = ShipmentItem::where('shipment_id', $value1['id'])->get();
			//if ($shipmentItem != '') {
	                //foreach ($shipmentItem as $k => $val) {
					$shipmentObject['weight'] = $value1->parcel_weight;
					$shipmentObject['height'] = $value1->dimension_height;
					$shipmentObject['width'] = $value1->dimension_width;
					$shipmentObject['length'] = $value1->dimension_length;
					$shipmentObject['value'] = $value1->total;
	                //}
	            //}
			$shipmentData[] = $shipmentObject;
            $input = [
              'CollectionAddress' => [
                  'Country' => 'GBR',
              ],
              'DeliveryAddress'   => [
                  'Country' => isset($delievery['short_code']) ? $delievery['short_code'] : 'IND' ,
              ],
              'Parcels'           => $shipmentData
          ];
            $result = getQuotes($token, json_encode($input));
            if (isset($result['Quotes']) && count($result['Quotes']) > 0) {
                foreach ($result['Quotes'] as $key => $value) {
                    if (isset($value['Service'])) {
                        $rst[$value1['id']][$key] = [
                          'name'          => $value['Service']['Name'],
                          'courier_name'  => $value['Service']['CourierName'],
                          'delivery_type' => $value['Service']['DeliveryType'],
                          'logo'          => $value['Service']['Links']['Imagelarge'],
                          'features'      => $value['Service']['Features'],
                          'service'       => $value['Service']['Classification'],
                      ];
                    }
                    $rst[$value1['id']][$key] += [
                      'estimated_delivery_date' =>  getBusinessDays(date('d-m-Y'),date('d-m-Y',strtotime($value['EstimatedDeliveryDate']))).' business days',
                      'price'                   => $value['TotalPrice'],
                  ];
                    $gpfChargeObject = GpfShipmentHandlingCharge::where('start_price', '<=', $value['TotalPrice'])
                            ->where('end_price', '>=', $value['TotalPrice']);
                    if (auth()->user() && auth()->user()->plan_type == "free" && (auth()->user()->membership_validity == null || auth()->user()->membership_validity <= date('Y-m-d H:i:s'))) {
                        $gpfChargeObject->where('type', 1);
                    } else {
                        $gpfChargeObject->where('type', 2);
                    }
                    $gpfCharge = $gpfChargeObject->first();
                    $price = isset($gpfCharge['gpf_price']) ? $gpfCharge['gpf_price'] : 0;
                    $rst[$value1['id']][$key] += [
                    'gpf_charges' => $price,
                    'total_price' => sprintf("%.2f", $price + $value['TotalPrice']),
                  ];
                }
            } else {
                $rst[$value1['id']][] = [
                  'name'          => config('app.name'),
                  'courier_name'  => config('app.name'),
                  'delivery_type' => 'Door',
                  'logo'          => asset('img/logo.jpg'),
                  'features'      => null,
                  'service'      => 'Fast',
                  'estimated_delivery_date' =>  date('d-m-Y H:i',strtotime('+'.config('site.estimated_delivery_period').' days')),
                  'price'                   => $value1['custom_shipping_price'],
                  'gpf_charges'              => NULL,
                  'total_price'              => $value1['custom_shipping_price'],
              ];
            }
        }
        $data['data'] = $rst;
        return Api::ApiResponse($data);
    }
}
