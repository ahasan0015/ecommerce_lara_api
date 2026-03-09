<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
    public function boot(): void
    {
        // লারাভেলকে বলা হচ্ছে পাজিনেশনের জন্য বুটস্ট্র্যাপ ৫ এর স্টাইল ব্যবহার করতে
        Paginator::useBootstrapFive();
    }
}
