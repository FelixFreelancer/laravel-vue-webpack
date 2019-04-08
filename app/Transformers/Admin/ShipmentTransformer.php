<?php
namespace App\Transformers\Admin;

class ShipmentTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'parcel_number' => $data['parcel_number'],
          'name' => $data['name'],
          'customer_id' => $data['user_id'],
          'customer_name' => $data['username'],
          'status' => config('site.shipment_statuses.'.$data['status']) ? config('site.shipment_statuses.'.$data['status']) : $data['status'],
          'received_on' => date('d/m/Y H:i',strtotime($data['received_on'])),
        ];
    }
}
