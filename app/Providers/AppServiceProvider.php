<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Laravel pagination Bootstrap 5
        Paginator::useBootstrapFive();

        Scramble::afterOpenApiGenerated(function ($openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
