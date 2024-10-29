<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Define model policies here
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
