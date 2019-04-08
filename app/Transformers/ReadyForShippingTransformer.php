<?php
namespace App\Transformers;

class ReadyForShippingTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'name' => $data['name'],
          'status' => $data['status'],
          'status_text' => $data['status'] == '3' ? 'Pending Verification' : ($data['status'] == '4' ? 'Processing' : NULL ),
          'parcel_desc' => $data['parcel_desc'],
          'dimension_length' => $data['dimension_length'],
          'dimension_width' => $data['dimension_width'],
          'dimension_height' => $data['dimension_height'],
          'parcel_weight' => $data['parcel_weight'],
          'shipping_out_company' => $data['shipping_out_company'],
          'shipping_out_amount' => appendCurrency($data['shipping_out_amount']),
          'total' => appendCurrency(number_format($data['total'],2)),
          'shipping_out_logo' => $data['shipping_out_logo'],
          'user_name' => $data['user_name'],
        ];
    }

}
