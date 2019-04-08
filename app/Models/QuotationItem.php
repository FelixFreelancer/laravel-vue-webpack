<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'store_name',
        'direct_link',
        'item_name',
        'user_price_currency',
        'user_price',
        'color',
        'quantity',
        'admin_price_currency',
        'admin_price',
        'admin_id',
    ];
}
