<?php
namespace App\Transformers\Admin;

class InvoiceTransformer
{
    public static function transform($data)
    {
        $text = '';
        $status= '';
        if($data['entity_type'] == 1){
          $text = 'Shipment';
          $status = (isset($data['entity']['status']) && $data['entity']['status'] >=3 ) ? 'Paid' : 'Outstanding';
        }else if($data['entity_type'] == 2){
          $text = 'Quotation';
		  $status = 'Paid';
	    }else if($data['entity_type'] == 3){
          $text = 'Membership';
          $status= 'Paid';
        }
        return [
          'id' => $data['id'],
          'user_name' => $data['user_name'],
          'user_id' => $data['user_id'],
          'invoice_number' => $data['invoice_number'],
          'invoice' => asset($data['invoice_path']."/".$data['invoice_name']),
          'entity_type' => $data['entity_type'],
          'type' => $text,
          'entity_id' => $data['entity_id'],
          'total' => appendCurrency(number_format($data['total'],2)),
          'invoice_date' => strtotime($data['created_at']),
          'status' => $status,
        ];
    }
}
