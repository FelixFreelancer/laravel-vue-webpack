<?php
namespace App\Transformers\Admin;

class UserTransformer
{
    public static function transform($data)
    {
        return [
			'id' => $data['id'],
          'customer_code' => $data['customer_code'],
          'name' => $data['name'],
          'email' => $data['email'],
          'contact_number' => $data['cd_country_code']." ".$data['cd_phone'],
          'country' => $data['country_name'],
          'customer_status' => getUserStatus($data),
        ];
    }
}
