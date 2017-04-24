<?php
/*
 * Carbonated
 *
 * This File belongs to to Project Carbonated
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

namespace ThisVessel;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the YourPackage service provider class.
 *
 * @author {{ author }}
 */
class CarbonatedServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/carbonated.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('carbonated.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('carbonated');
        }
        $this->mergeConfigFrom($source, 'carbonated');
    }
}
