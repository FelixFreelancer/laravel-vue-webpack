<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Library\Api;

/**
 * @resource Role
 *
 */

class PermissionController extends Controller
{
    /**
     * List Role
     *
     * This endpoint will list the role.
     */
    public function index()
    {
        $data['data'] = Permission::select('id', 'name')->get();
        return Api::ApiResponse($data);
    }

}
