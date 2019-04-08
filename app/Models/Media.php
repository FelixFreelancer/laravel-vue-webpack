<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $table='medias';

    protected $fillable = [
        'main_id',
        'type',
        'media_path',
        'media_name',
        'media_type',
    ];
}
