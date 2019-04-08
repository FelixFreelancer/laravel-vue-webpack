<?php
namespace App\Transformers;

class WarehouseTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'name' => $data['name'],
          'status' => $data['status'],
          'items' => WarehouseTransformer::getItem($data['items']),
          'parcel_number' => $data['parcel_number'],
          'parcel_desc' => $data['parcel_desc'],
          'dimension_length' => $data['dimension_length'],
          'dimension_width' => $data['dimension_width'],
          'dimension_height' => $data['dimension_height'],
          'parcel_weight' => $data['parcel_weight'],
          'postal_company' => $data['postal_company'],
          'shipping_out_company' => $data['shipping_out_company'],
          'shipping_out_amount' => appendCurrency($data['shipping_out_amount']),
          'shipping_out_logo' => $data['shipping_out_logo'],
          'total' => number_format($data['total'],2),
          'received_on' => date('d-m-Y H:i',strtotime($data['received_on'])),
          'invoice' => ($data['invoice_name'] != '') ? asset($data['invoice_path']."/".$data['invoice_name']) : NULL,
          'image' => WarehouseTransformer::getShipmentMedia($data['medias']),
          'items' => WarehouseTransformer::getItem($data['items']),
        ];
    }

    public static function getShipmentMedia($media){
      $images = [];
      foreach($media as $key => $value){
        $images[] = asset($value->media_path.$value->media_name);
      }
      return $images;
    }

    public static function getItem($items){
      $itemObject = [];
      foreach($items as $key => $value){
        $itemObject[] = [
          'id' => $value['id'],
          'name' => $value['item_name'],
          'qty' => $value['qty'],
          'amount' =>appendCurrency($value['amount']),
          'desc' => $value['desc'],
          'dimension_length' => $value['dimension_length'],
          'dimension_width' => $value['dimension_width'],
          'dimension_height' => $value['dimension_height'],
          'weight' => $value['weight'],
          'tracking_number' => $value['tracking_number'],
        ];
      }
      return $itemObject;
    }

}
