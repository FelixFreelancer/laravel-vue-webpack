<?php
namespace App\Transformers;

class ActionBoxTransformer
{
    public static function transform($data)
    {

        $insurance_charge=0;
        if(isset($data['items']))
        {
          $rst=ActionBoxTransformer::getItem($data['items']);
          $amount=0;
          foreach($rst as $key=>$value)
          {
            $amount +=$value['total'];
          }
          $insurance_charge=$amount*config('site.insurance_charge')/100;
        }
        return [
          'id' => $data['id'],
          'name' => $data['name'],
          'status' => $data['status'],
          'parcel_number' => $data['parcel_number'],
          'parcel_desc' => $data['parcel_desc'],
          'parcel_display_desc' => substr($data['parcel_desc'],0,100),
          'parcel_sub_desc' => substr($data['parcel_desc'],0,100),
          'dimension_length' => $data['dimension_length'],
          'dimension_width' => $data['dimension_width'],
          'dimension_height' => $data['dimension_height'],
          'parcel_weight' => $data['parcel_weight'],
          'postal_company' => $data['postal_company'],
          'received_on' => date('d-m-Y H:i',strtotime($data['received_on'])),
          'total' => appendCurrency($data['total']),
          'insurance_charges' =>  $insurance_charge,
          'image' => isset($data['medias']) ? ActionBoxTransformer::getShipmentMedia($data['medias']) : NULL,
          'items' => isset($data['items']) ? ActionBoxTransformer::getItem($data['items']) : NULL,
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
      $user = auth()->user();
      foreach($items as $key => $value){
        $item = [
          'id' => $value['id'],
          'name' => $value['item_name'],
          'qty' => $value['qty'],
          'amount' => appendCurrency($value['amount']),
          'total' =>$value['amount'],
          'desc' => $value['desc'],
          'dimension_length' => $value['dimension_length'],
          'dimension_width' => $value['dimension_width'],
          'dimension_height' => $value['dimension_height'],
          'weight' => $value['weight'],
          'tracking_number' => $value['tracking_number'],
        ];
        if($user->plan_type == "paid"){
          $item += [
            'image' => isset($value['medias']) ?  ActionBoxTransformer::getShipmentItemMedia($value['medias']) : NULL,
          ];
        }
        $itemObject[] = $item;
      }
      return $itemObject;
    }

    public static function getShipmentItemMedia($media){
      $images = [];
      foreach($media as $key => $value){
        $images[] = asset($value->media_path.$value->media_name);
      }
      return $images;
    }
}
