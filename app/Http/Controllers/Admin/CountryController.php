<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    public function index()
    {
        return view('admin.pages.countries.index');
    }

    public function create()
    {
        return view('admin.pages.countries.create');
    }

    public function store()
    {
        $this->validate(request(), Country::validationRules());

        $country = new Country(request()->all());
        if (!$country->save()) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
        }
        session()->flash('message', 'Country added successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.countries.index');
    }

    public function edit(Country $country)
    {
        $data = [];
        $data['country'] = $country;
        return view('admin.pages.countries.create', $data);
    }

    public function update(Country $country)
    {
        $this->validate(request(), Country::validationRules($country->id));

        $inputs = request()->all();

        if (!$country->update($inputs)) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }
        session()->flash('message', 'Country updated successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.countries.index');
    }

    public function destroy(Country $country)
    {
        $data = [];
        $data['status'] = $country->delete();
        return $data;
    }

    public function indexDatatable()
    {
        $data = Country::select('*');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $str = '';
                $str .= '<a href="' . url()->route('admin.countries.edit', $row->id) . '"  data-id="' . $row->id . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
							<i class="la la-edit"></i>
						</a>
						<a href="#" data-id="' . $row->id . '" class="deleteCountry m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
							<i class="la la-trash"></i>
						</a>';
                return $str;
            })
            ->make(true);
    }
}
