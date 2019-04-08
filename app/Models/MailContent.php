<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class MailContent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'subject',
        'description',
    ];

}
