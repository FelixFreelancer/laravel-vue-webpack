<?php
namespace App\Transformers;

class ProfileTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'auto_renew' => $data['auto_renew'],
          'membership_validity' => $data['membership_validity'] != NULL ? date('M d,Y',strtotime($data['membership_validity'])) : NULL,
          'membership_validity_time' => $data['membership_validity'],
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email'],
          'gender' => $data['gender'],
          'company_name' => $data['company_name'],
          'cd_address' => $data['cd_address'],
          'cd_country' => $data['cd_country'],
          'cd_country_code' => $data['cd_country_code'],
          'cd_state' => $data['cd_state'],
          'cd_city' => $data['cd_city'],
          'cd_postalcode' => $data['cd_postalcode'],
          'cd_phone' => $data['cd_phone'],
          'cd_phone_verified_at' => $data['cd_phone_verified_at'],
          'plan_type' => $data['plan_type'],
          'customer_number' => $data['customer_number'],
          'suite_number' => $data['suite_number'],
          'image_name' => $data['image_name'] != '' ? asset($data['image_path'].$data['image_name']) : NULL,
        ];
    }
}
