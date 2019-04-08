<?php
namespace App\Transformers\Admin;

class ShipmentItemTransformer
{

    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'shipment_id' => $data['shipment_id'],
          'item_name' => $data['item_name'],
          'shipment_name' => $data['shipment_name'],
          'amount' => appendCurrency($data['amount']),
          'user_name' => $data['user_name'],
		  'customer_id' => $data['user_id'],
          'customer_code' => $data['customer_code'],
          'tracking_number' => $data['tracking_number'],
        ];
    }
}
