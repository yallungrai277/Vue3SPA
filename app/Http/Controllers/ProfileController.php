<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());
        return response()->json((new UserResource($request->user())), 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->validated()['password'])
        ]);
        return response()->noContent();
    }
}