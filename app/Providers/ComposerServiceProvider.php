<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            [
                'admin.includes.header',
                'frontend.includes.header',
            ], 'App\ViewComposers\HeaderComposer'
        );

        view()->composer(
            [
                'frontend.partials.profile.user_info',
            ], 'App\ViewComposers\WarehouseAddressComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
