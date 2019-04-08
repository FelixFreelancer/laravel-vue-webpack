<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;

class WarehouseController extends Controller
{
    public function index()
    {
        $data = [];
        $data['option'] = Option::where('type', '=', 1)->pluck('value', 'key');
        return view('admin.pages.warehouse.index', $data);
    }

    public function store()
    {
        $this->validate(request(), Option::ukWarehouseAddressValidationRules());

        $options = Option::where('type', '=', '1')->get();

        foreach ($options as $key => $value) {
            $value->update([
                'value' => request($value->key)
            ]);
        }
        session()->flash('message', 'Warehouse address updated successfully.');
        session()->flash('class', 'success');
        return back();
    }
}
