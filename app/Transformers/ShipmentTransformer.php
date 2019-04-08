<?php
namespace App\Transformers;

class ShipmentTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'name' => $data['name'],
          'status' => $data['status'],
          'parcel_number' => $data['parcel_number'],
          'user_name' => $data['user_name'],
          'parcel_desc' => $data['parcel_desc'],
          'dimension_length' => $data['dimension_length'],
          'dimension_width' => $data['dimension_width'],
          'dimension_height' => $data['dimension_height'],
          'parcel_weight' => $data['parcel_weight'],
          'postal_company' => $data['postal_company'],
          'total' => appendCurrency(number_format($data['total'],2)),
          'shipping_out_company' => $data['shipping_out_company'],
          'shipping_out_amount' => appendCurrency($data['shipping_out_amount']),
          'shipping_out_logo' => $data['shipping_out_logo'],
          'shipping_out_at' => date('d-m-Y H:i',strtotime($data['shipping_out_at'])),
          'shipping_out_tracking' => $data['shipping_out_tracking'],
          'shipping_out_service' => $data['shipping_out_service'],
          'shipping_tracking_link' => $data['shipping_tracking_link'],
          'received_on' => date('d-m-Y H:i',strtotime($data['received_on'])),
		      'items' => ShipmentTransformer::getItem($data['items']),
        ];
    }

    public static function getItem($items){
      $itemObject = [];
      foreach($items as $key => $value){
		  $total = $value['amount'] *  $value['qty'];
        $itemObject[] = [
          'id' => $value['id'],
          'name' => $value['item_name'],
          'qty' => $value['qty'],
          'amount' =>appendCurrency($value['amount']),
          'total' =>appendCurrency($total),
        ];
      }
      return $itemObject;
    }

}
