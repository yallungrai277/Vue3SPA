<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\RolePermissionUpdateRequest;
use App\Http\Resources\Role\RoleResource;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {

        $roles = Role::with('permissions')->get();
        return response()->json([
            'data' => RoleResource::collection($roles),
            'status' => true
        ], 200);
    }

    public function update(RolePermissionUpdateRequest $request)
    {
        $data = $request->validated()['roles_permission'];

        $roleId = Role::where('name', Role::ADMIN_ROLE)->value('id');
        if (is_null($roleId)) {
            throw new DomainException('The server is not configured properly to handle roles and permissions. Please contact admin.');
        }

        if (isset($data[$roleId])) {
            unset($data[$roleId]);
        }

        foreach ($data as $roleId => $permissionIds) {
            $role = Role::findOrFail($roleId);
            $role->permissions()->sync($permissionIds);
        }

        return $this->index($request);
    }
}