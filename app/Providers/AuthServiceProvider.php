<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin',function ($id){
            return $id->hasAnyRoles(['Admin','Kasi']);
        });

        Gate::define('management',function ($id){
            return $id->hasAnyRoles(['Admin','Kasi','Kanit']);
        });

        Gate::define('user', function ($id){
            return $id->hasAnyRoles(['Admin','Kanit','Kasi','Petugas']);
        });
    }
}
