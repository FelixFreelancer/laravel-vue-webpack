<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Library\Api;
use DB;
/**
 * @resource Role
 *
 */

class RoleController extends Controller
{
    /**
     * List Role
     *
     * This endpoint will list the role.
     */
    public function index()
    {
		$excluded = [2];
		$loggedInUser = Api::getAuthenticatedUser();
		$user = $loggedInUser['user'];
		if($user->hasRole('Super Admin')){
			$data['data'] = Role::select('id', 'name')->whereNotIn('id',$excluded)->get();
		}else{
			$roles = $user->roles;
			$accesible_roles = explode(",",$roles[0]['access_roles']);
			$data['data'] = Role::select('id', 'name')->whereIn('id',$accesible_roles)->get();
		}
        return Api::ApiResponse($data);
    }

    public function store(StoreRequest $request)
    {
        $role = Role::create(['name' => $request['name']]);
        $role->givePermissionTo($request['permissions']);
        $data['data'] = $role->toArray();
        return Api::ApiResponse($data);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if(!$role){
          $data['data']['error'] = "Role Not Found";
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $permission =  DB::table('role_has_permissions')
                      ->leftJoin('permissions','permissions.id','=','role_has_permissions.permission_id')
                      ->select('role_has_permissions.permission_id as id','permissions.name')
                      ->where('role_has_permissions.role_id',$id)
                      ->get();
        $data['data'] = [
          'role' => $role->toArray(),
          'permission' => $permission->toArray(),
        ];
        return Api::ApiResponse($data);
    }

    public function update($id,UpdateRequest $request)
    {
        $role = Role::find($id);
        $role->name = $request['name'];
        $role->save();
        $role->syncPermissions($request['permissions']);
		$permission =  DB::table('role_has_permissions')
                      ->leftJoin('permissions','permissions.id','=','role_has_permissions.permission_id')
                      ->where('role_has_permissions.role_id',$id)
                      ->pluck('permissions.name');
        $data['data'] = [
          'role' => $role->toArray(),
          'permissions' => $permission->toArray(),
        ];
        return Api::ApiResponse($data);
    }


}
