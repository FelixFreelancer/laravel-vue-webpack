<?php
namespace App\Transformers;

class InvoiceTransformer
{
    public static function transform($data)
    {
        $result = [
          'id' => $data['id'],
          'invoice_number' => $data['invoice_number'],
          'created_at' => date('d-m-Y',strtotime($data['created_at'])),
          'invoice' => ($data['invoice_name'] != '') ? asset($data['invoice_path']."/".$data['invoice_name']) : NULL,
          'entity_type' => $data['entity_type'],
        ];
        if($data['entity_type'] == "1"){
          $result += [
		  	    'entity_type_text' => 'Shipping Cost',
            'shipment' => $data['entity'],
            'is_paid' => ( isset($data['entity']['status']) && $data['entity']['status'] >=3 ) ? true:false,
          ];
	  }else if($data['entity_type'] == "2"){
          $result += [
		  	    'entity_type_text' => 'Personal Shopping',
            'items' => $data['entity'],
            'is_paid' => true,
          ];
	  }else{
		  $result += [
			  'entity_type_text' => 'Membership',
			  'total' => $data['total'] != NULL ? config('site.default_currency').$data['total'] : NULL,
			  'desc' => 'Monthly Subscription For '.date('d-m-Y',strtotime($data['created_at']))." to ".date('d-m-Y', strtotime('+'.config('site.membership_validity').' month', strtotime($data['created_at']))),
        'is_paid' => true,
          ];
	  }
        return $result;
    }

}
