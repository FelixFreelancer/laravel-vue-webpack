<?php
namespace App\Transformers\Admin;

class PhotoRequestTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'shipment_id' => $data['shipment_id'],
          'name' => $data['item_name'],
          'user_name' => $data['user_name'],
          'user_id' => $data['user_id'],
          'parcel_number' => $data['parcel_number'],
          'requested_at' => strtotime($data['requested_at'])
        ];
    }
}
