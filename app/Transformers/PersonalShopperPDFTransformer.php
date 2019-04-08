<?php
namespace App\Transformers;

class PersonalShopperPDFTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'quotation_number' => $data['quotation_number'],
          'total' => appendCurrency($data['total']),
          'items' => PersonalShopperPDFTransformer::getItems($data['items']),
        ];
    }
    public static function getItems($items)
    {
      $itemObj = [];
      foreach($items as $key => $value){
        $itemObj[] = [
          'item_name' => $value['item_name'],
          'qty' => $value['quantity'],
          'admin_price' => appendCurrency($value['admin_price']),
          'subtotal' => appendCurrency($value['admin_price']*$value['quantity']),
        ];
      }
      return $itemObj;
    }

}
