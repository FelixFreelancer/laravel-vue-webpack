<?php

namespace App\ViewComposers;


use App\Models\Option;
use Illuminate\View\View;

class WarehouseAddressComposer
{
    public function compose(View $view)
    {
        $data = [];
        $data['address'] = Option::where('type', '=', '1')->pluck('value', 'key');
        $view->with($data);
    }
}