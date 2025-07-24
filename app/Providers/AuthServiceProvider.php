<?php

namespace App\Providers;

use Jeremy379\OpenIdConnect\Facades\OpenIdConnect;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;



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

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Configuration des claims pour OpenID Connect
        // OpenIdConnect::claims(function ($user, $scopes) {
        //     return [
        //         'sub' => $user->id,
        //         'email' => $user->email,
        //         'given_name' => $user->name,
        //         'family_name' => '',
        //         'admin' => false,
        //     ];
        // });
    }
}
