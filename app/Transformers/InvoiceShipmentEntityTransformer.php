<?php
namespace App\Transformers;

class InvoiceShipmentEntityTransformer
{
    public static function transform($data)
    {
        return [
          'name' => $data['name'],
          'parcel_number' => $data['parcel_number'],
          'shipped_by' => $data['user_name'],
          'status' => $data['status'],
          'total' => appendCurrency(number_format($data['total'],2)),
        ];
    }

}
