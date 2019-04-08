<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status_id',
        'status_by',
        'deleted_by',
        'shipment_id',
        'notes',
    ];
}
