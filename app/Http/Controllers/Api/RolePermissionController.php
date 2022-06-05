<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\RolePermissionUpdateRequest;
use App\Http\Resources\Role\RoleResource;
use DomainException;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('isAdmin');
        $roles = Role::with('permissions')->get();
        return response()->json([
            'data' => RoleResource::collection($roles),
            'status' => true
        ], 200);
    }

    public function update(RolePermissionUpdateRequest $request)
    {
        $this->authorize('isAdmin');
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