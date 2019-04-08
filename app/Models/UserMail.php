<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMail extends Model
{
    use SoftDeletes;

    protected $table = 'mails';
    protected $fillable = [
        'sent_by',
        'user_id',
        'subject',
        'mail',
    ];

}
