<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lukaswhite\UkPostcodeGeocoder\Service;

/**
 * Class AppServiceProvider
 *
 * The application service provider.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the postcodes service
        $this->app->singleton( Service::class, function( $app ) {
            return new Service(
                env( 'DATABASE_PATH', storage_path( 'database' ) ),
                env( 'DATABASE_FILENAME', 'postcodes.sqlite' )
            );
        } );
    }
}
