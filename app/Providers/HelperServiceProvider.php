<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BahtText;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the baht_text helper function
        if (!function_exists('baht_text')) {
            function baht_text($number) {
                return BahtText::convert($number);
            }
        }
    }
}
