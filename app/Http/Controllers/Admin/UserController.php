<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $data = [];
        $data['roles'] = config('site.roles');
        return view('admin.pages.users.index', $data);
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function edit(User $user)
    {

    }

    public function update(User $user)
    {

    }

    public function destroy(User $user)
    {

    }

    public function indexDatatable()
    {
        $data = User::select('*')
            ->where('role_id', '=', '2');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $str = '';
//                $str .= '<a href="' . url()->route('admin.users.edit', $row->id) . '"  data-id="' . $row->id . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
//							<i class="la la-edit"></i>
//						</a>
//						<a href="#" data-id="' . $row->id . '" class="deleteIndicator m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
//							<i class="la la-trash"></i>
//						</a>';
                $str .= '<a href="#nogo"  data-id="' . $row->id . '" class="viewUserInfo m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
							<i class="la la-eye"></i>
						</a>';
                return $str;
            })
            ->make(true);
    }

    public function ajaxShow(User $user)
    {
        $data = [];
        $user->first_name = ucwords(strtolower($user->first_name));
        $user->last_name = ucwords(strtolower($user->last_name));
        $user->gender = config('site.gender')[$user->gender];

        $cd_country = Country::find($user->cd_country);
        if ($cd_country != null) {
            $user->cd_country = $cd_country->name;
            $user->cd_country_code = $cd_country->country_code;
            $user->cd_suite_number = $cd_country->suite_number;
        }

        $ba_country = Country::find($user->ba_country);
        if ($ba_country != null) {
            $user->ba_country = $ba_country->name;
            $user->ba_country_code = $ba_country->country_code;
            $user->ba_suite_number = $ba_country->suite_number;
        }

        $data['user'] = $user;
        return $data;
    }

    public function search()
    {
        $data = [];

        $data['items'] = User::select(DB::raw('concat(first_name," ",last_name,"-",customer_code) as name'), 'id')
            ->where('first_name', 'like', '%' . request('q') . '%')
            ->orwhere('last_name', 'like', '%' . request('q') . '%')
            ->orwhere(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . request('q') . '%')
            ->orwhere('customer_code', 'like', '%' . request('q') . '%')
            ->get();

        return $data;
    }
}
