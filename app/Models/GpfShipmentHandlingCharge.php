<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpfShipmentHandlingCharge extends Model
{
    protected $fillable = [
        'start_price',
        'end_price',
        'type',
        'gpf_price'
    ];
}
