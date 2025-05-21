<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TenantUser;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for roles
        Gate::define('manage-products', function (TenantUser $user) {
            return $user->hasRole('admin') || $user->hasRole('tenant-admin') || $user->hasRole('pharmacist');
        });

        Gate::define('access-sales', function (TenantUser $user) {
            return $user->hasRole('admin') || $user->hasRole('tenant-admin') || $user->hasRole('pharmacist');
        });
    }
}
