<?php
namespace App\Transformers;

class UserTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'plan_type' => $data['plan_type'],
        ];
    }
}
