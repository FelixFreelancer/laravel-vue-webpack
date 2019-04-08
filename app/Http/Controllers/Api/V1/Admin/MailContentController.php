<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailContent;
use App\Library\Api;


class MailContentController extends Controller
{

    public function index()
    {
        $mail = MailContent::select('id as value','type as text','subject','description')->get();
        $data['data'] = $mail;
        return Api::ApiResponse($data);
    }

}
