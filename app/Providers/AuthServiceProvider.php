<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user-company', function ($user) {
            $user = \App\Models\User::where('id', $user->id)->first();
            return (is_null($user)) ? false : true;
        });

        Gate::define('isAdmin', function($user) {
            $user = \App\Models\User::find($user->id);
            if ($user->hasRole('Super Admin') || $user->hasRole('Ops Admin')) {
                return true;
            }
            return false;
        });

        Gate::define('manageUser', function ($user) {
            $user = \App\Models\User::find($user->id);
            if ($user->hasRole('Super Admin') || $user->hasRole('Ops Admin') || $user->hasRole('Company Admin')) {
                return true;
            }
            return false;
        });
    }
}
