<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Warehouse\StoreRequest;
use App\Models\Option;
use App\Library\Api;

class WarehouseController extends Controller
{
    public function index()
    {
        $loggedInUser = Api::getAuthenticatedUser();

        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Warehouse Listing','<b>'.$name."</b> has viewed <b>Warehouse Tab</b>.");

        $data['data'] = Option::where('type', '=', 1)->pluck('value', 'key');
        return Api::ApiResponse($data);
    }

    public function store(StoreRequest $request)
    {
        $data['data'] = [];
        $options = Option::where('type', '=', '1')->get();
        foreach ($options as $key => $value) {
            $value->update([
                'value' => request($value->key)
            ]);
        }

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Warehouse Update','Warehouse details has been updated by <b>'.$name.'</b>');
        $data['data'] = $options->pluck('value', 'key');
        return Api::ApiResponse($data);
    }
}
