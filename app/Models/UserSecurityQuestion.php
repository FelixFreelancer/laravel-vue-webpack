<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurityQuestion extends Model
{
    protected $fillable = [
        'user_id',
        'security_question_id',
        'answer'
    ];

}
