<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key',
        'value'
    ];

    public static function ukWarehouseAddressValidationRules()
    {
        return [
            'uk_warehouse_address_line_1' => 'required|max:1000',
            'uk_warehouse_address_line_2' => 'required|max:1000',
            'uk_warehouse_country'        => 'required|max:1000',
        ];
    }
}
