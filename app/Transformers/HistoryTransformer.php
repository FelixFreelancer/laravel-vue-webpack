<?php
namespace App\Transformers;

class HistoryTransformer
{
    public static function transform($data)
    {
        $status = config('site.shipment_statues');
        return [
          'id' => $data['id'],
          'parcel_number' => $data['parcel_number'],
		  'delivered_at' => $data['delivered_at'] != NULL ? date('F jS Y',strtotime($data['delivered_at'])) : NULL,
		  'status' => isset($status[$data['status']]) ? $status[$data['status']] : 'Delivered',
          'shipping_out_company' => $data['shipping_out_company'],
          'shipping_out_tracking' => $data['shipping_out_tracking'],
        ];
    }

}
