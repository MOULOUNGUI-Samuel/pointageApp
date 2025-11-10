<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConformiteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('Helpers/ConformiteHelpers.php');
    }

    public function boot(): void
    {
        //
    }
}