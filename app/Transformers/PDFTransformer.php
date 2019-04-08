<?php
namespace App\Transformers;

class PDFTransformer
{
    public static function transform($data)
    {
        $shippingCost = $data['shipping_out_amount'] + $data['gpf_charge'];
        return [
          'id' => $data['id'],
          'name' => $data['name'],
          'parcel_number' => $data['parcel_number'],
          'parcel_desc' => $data['parcel_desc'],
          'status' => $data['status'],
          'user_name' => $data['user_name'],
          // 'dimension_length' => $data['dimension_length'],
          // 'dimension_width' => $data['dimension_width'],
          // 'dimension_height' => $data['dimension_height'],
          // 'parcel_weight' => $data['parcel_weight'],
          'postal_company' => $data['postal_company'],
          'received_on' => strtotime($data['received_on']),
          'shipping_charge' => appendCurrency(number_format($shippingCost,2)),
          'total' => appendCurrency(number_format($data['total'],2)),
          'insurance_charge' => appendCurrency(number_format($data['insurance_charge'],2)),
        ];
    }

}
