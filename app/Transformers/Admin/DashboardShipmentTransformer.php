<?php
namespace App\Transformers\Admin;

class DashboardShipmentTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'user_id' => $data['user_id'],
          'username' => $data['username'],
          'parcel_number' => $data['parcel_number'],
          'status' => $data['status'],
          'status_text' => config('site.shipment_statuses.'.$data['status']) ? config('site.shipment_statuses.'.$data['status']) : NULL,
          'received_on' => strtotime($data['received_on']),
        ];
    }
}
