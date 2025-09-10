<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Partager automatiquement les variables de session avec toutes les vues
        View::composer('*', function ($view) {
            $view->with('module_nom', Session::get('module_nom'));
            $view->with('module_logo', Session::get('module_logo'));
            $view->with('module_id', Session::get('module_id'));
            $view->with('entreprise_nom', Session::get('entreprise_nom'));
            $view->with('entreprise_logo', Session::get('entreprise_logo'));
            $view->with('entreprise_code', Session::get('entreprise_code'));
            $view->with('entreprise_id', Session::get('entreprise_id'));
        });
    }
}
