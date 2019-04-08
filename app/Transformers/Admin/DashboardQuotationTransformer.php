<?php
namespace App\Transformers\Admin;

class DashboardQuotationTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'user_id' => $data['user_id'],
          'username' => $data['username'],
          'quotation_number' => $data['quotation_number'],
          'price' => appendCurrency($data['user_price']),
          'requested_on' => strtotime($data['created_at']),
        ];
    }
}
