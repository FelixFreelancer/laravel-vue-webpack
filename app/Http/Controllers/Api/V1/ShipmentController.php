<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shipment\ShippingOptionRequest;
use App\Models\User;
use App\Models\Country;
use App\Library\Api;

class ShipmentController extends Controller
{
    public function shippingOptions(ShippingOptionRequest $request)
    {
        $token = getAuthToken();
		$user  = User::find($request['user_id']);
		if($user){
			$delievery = Country::find($user['cd_country']);
		}
		$shipmentObject['weight'] = $request['parcel_weight'];
		$shipmentObject['height'] = $request['dimension_height'];
		$shipmentObject['width'] = $request['dimension_width'];
		$shipmentObject['length'] = $request['dimension_length'];
		$shipmentObject['value'] = 100;
		$shipment[] = $shipmentObject;
		$input = [
			'CollectionAddress' => [
				'Country' => 'GBR',
			],
			'DeliveryAddress'   => [
				'Country' => isset($delievery['short_code']) ? $delievery['short_code'] : 'IND' ,
			],
			'Parcels'           => $shipment
		];
		$result = getQuotes( getAuthToken() , json_encode($input));
		$data['data']['is_option_available'] = false;
        if (isset($result['Quotes']) && count($result['Quotes']) > 0) {
            $data['data']['is_option_available'] = true;
        }
        return Api::ApiResponse($data);
    }

}
