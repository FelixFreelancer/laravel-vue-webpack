<?php
namespace App\Transformers\Admin;

class CustomerTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email'],
          'cd_phone' => $data['cd_country_code']." ".$data['cd_phone'],
          'role' => $data['role'],
          'customer_code' => $data['customer_code'],
          'plan_type' => $data['plan_type'],
        ];
    }
}
