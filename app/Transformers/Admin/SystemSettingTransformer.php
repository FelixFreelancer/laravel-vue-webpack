<?php
namespace App\Transformers\Admin;

class SystemSettingTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'name' => ucwords(str_replace("_"," ",$data['name'])),
          'value' => $data['value'],
        ];
    }
}
