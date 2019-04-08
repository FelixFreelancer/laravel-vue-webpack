<?php
namespace App\Transformers;

class QuotationTransformer
{
    public static function transform($data)
    {
        $text = '';
        if($data['status'] == 1){
          $text = 'Quote Requested';
        }else if($data['status'] == 2){
          $text = 'Pay';
        }else if($data['status'] == 3){
          $text = 'Paid';
        }
        return [
          'id' => $data['id'],
          'quotation_number' => $data['quotation_number'],
          'total' => isset($data['total']) ? appendCurrency($data['total']) : NULL,
          'status' => $data['status'],
          'status_text' => $text,
          'items' => isset($data['items']) ? QuotationTransformer::getItem($data['items']) : NULL
        ];
    }

    public static function getItem($data)
    {
      $result = [];
      foreach($data as $key => $value){
        $result[] = [
          'id' => $value['id'],
          'store_name' => $value['store_name'],
          'direct_link' => $value['direct_link'],
          'item_name' => $value['item_name'],
          'color' => $value['color'],
          'user_price' => $value['user_price_currency'].$value['user_price'],
          'status' => $value['status'],
          'quantity' => $value['quantity'],
          'admin_price' => $value['admin_price_currency'].$value['admin_price'],
        ];
      }
        return $result;
    }

}
