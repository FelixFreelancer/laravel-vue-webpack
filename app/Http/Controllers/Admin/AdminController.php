<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        $data = [];
        $data['roles'] = config('site.roles');
        return view('admin.pages.admins.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['roles'] = config('site.roles');
        return view('admin.pages.admins.create', $data);
    }

    public function store()
    {
        $this->validate(request(), User::adminValidationRules());

        $admin = new User(request()->all());
        $admin->role_id = 1;
        $admin->email_verified_at = date_time_database('now');
        $admin->cd_phone_verified_at = date_time_database('now');
        if (!$admin->save()) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
        }
        session()->flash('message', 'Admin added successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.admins.index');
    }

    public function edit(User $admin)
    {
        $data = [];
        $data['roles'] = config('site.roles');
        $data['admin'] = $admin;
        return view('admin.pages.admins.create', $data);
    }

    public function update(User $admin)
    {
        $this->validate(request(), User::adminValidationRules($admin->id));

        $inputs = request()->except('password');

        if (request('password')) {
            $inputs['password'] = request('password');
        }

        if (!$admin->update($inputs)) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }
        session()->flash('message', 'Admin updated successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.admins.index');
    }

    public function destroy(User $admin)
    {
        $data = [];
        $data['status'] = false;
        if ($admin->role_id == 1) {
            $data['status'] = $admin->delete();
        }
        return $data;
    }

    public function indexDatatable()
    {
        $data = User::select('*')
            ->where('role_id', '=', '1');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $str = '';
                $str .= '<a href="' . url()->route('admin.admins.edit', $row->id) . '"  data-id="' . $row->id . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
							<i class="la la-edit"></i>
						</a>
						<a href="#" data-id="' . $row->id . '" class="deleteAdmin m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
							<i class="la la-trash"></i>
						</a>';
                return $str;
            })
            ->make(true);
    }
}
