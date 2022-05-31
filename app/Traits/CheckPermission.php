<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;

trait CheckPermission
{
    public function checkPermission(User $user, String $permissionName, bool $throwException = true)
    {
        if ($user->isAdmin())
            return true;

        $hasPermission =  $user->roles()->whereHas('permissions', function (Builder $query) use ($permissionName) {
            return $query->where('name', $permissionName);
        })->first() ? true : false;

        if ($hasPermission)
            return true;

        if ($throwException)
            throw new AuthorizationException("This action is unauthorized.", 403);

        return false;
    }
}