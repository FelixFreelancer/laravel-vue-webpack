<?php
namespace App\Transformers\Admin;

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
          'customer_name' => $data['user_name'],
		  'customer_id' => $data['user_id'],
          'customer_code' => $data['customer_code'],
          'total' => appendCurrency($data['total']),
          'status' => $text,
          'requested_on' => date('d/m/Y H:i',strtotime($data['created_at'])),
        ];
    }
}
