<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\PermissionResource;

class PermissionController extends Controller
{
    public function __invoke()
    {
        return response()->json(['data' => PermissionResource::collection(Permission::all()), 'status' => true], 200);
    }
}