<?php

namespace Sukohi\QuickDict;

use Illuminate\Support\ServiceProvider;
use Sukohi\QuickDict\Commands\QuickDictCommand;
use Sukohi\QuickDict\Commands\QuickDictRefreshCommand;

class QuickDictServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var  bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                QuickDictCommand::class,
                QuickDictRefreshCommand::class,
            ]);
        }
        $this->publishes([
            __DIR__.'/config/quick_dict.php' => config_path('quick_dict.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('quick-dict', function()
        {
            return new QuickDict;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['quick-dict'];
    }

}