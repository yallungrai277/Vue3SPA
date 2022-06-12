<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\PermissionResource;

class UserPermissionController extends Controller
{

    public function __invoke(Request $request)
    {
        $permissions = $request->user()
            ->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten();

        return response()->json([
            'data' => PermissionResource::collection($permissions),
            'status' => true
        ], 200);
    }
}