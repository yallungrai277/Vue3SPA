<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public static function initAvailablePermissionsForSeeding()
    {
        return config('permissions');
    }

    public static function initUserUnallowedPermissionsForSeeding()
    {
        $permissions = config('permissions');
        return array_merge([
            ...$permissions['categories'],
            ...$permissions['permissions']
        ], []);
    }
}