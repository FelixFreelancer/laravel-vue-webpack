<?php
namespace App\Transformers;

class InvoiceShopperEntityTransformer
{
    public static function transform($data)
    {
        return [
          'quotation_number' => $data['quotation_number'],
          'item_name' => $data['item_name'],
          'quantity' => $data['quantity'],
          'rate' => appendCurrency(number_format($data['admin_price'],2)),
          'subtotal' => appendCurrency(number_format($data['admin_price']*$data['quantity'],2)),
          'total' => appendCurrency(number_format($data['total'],2)),
        ];
    }

}
