<?php namespace Motibu;

use Illuminate\Support\ServiceProvider;

class MotibuServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPermitter();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['permitter'];
    }

    /**
     * Register the permitter
     */
    protected function registerPermitter()
    {
        $this->app->bind('permitter', function () {
            return new Permitters\Permitter();
        });
    }
}
