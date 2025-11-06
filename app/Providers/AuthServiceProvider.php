<?php

namespace App\Providers;

use Jeremy379\OpenIdConnect\Facades\OpenIdConnect;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * Ce tableau est utilisé pour mapper vos modèles à leurs "Policies" d'autorisation.
     * Vous pouvez le laisser vide si vous n'utilisez pas les Policies.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

    }
}
