<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Post::class => PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissionGates();
    }

    public function registerPermissionGates()
    {
        // Global Permission Handler

        // Using trait CheckPermission for now to account for permission and model policy without having to call permission and policy twice.

        // Gate::before(function ($user, $ability) {
        //     if ($user->isAdmin()) {
        //         return true;
        //     }
        // });

        // foreach (Permission::pluck('name') as $permission) {
        //     Gate::define($permission, function ($user) use ($permission) {
        //         return $user->roles()->whereHas('permissions', function (Builder $query) use ($permission) {
        //             return $query->where('name', $permission);
        //         })->first() ? true : false;
        //     });
        // }

        Gate::define('isAdmin', function ($user) {
            return $user->isAdmin();
        });
    }
}