<?php

namespace Prothos\Moip\Providers;

//use Artesaos\Moip\Moip;
use Prothos\Moip\MoipOld;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class MoipServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // $config_file = __DIR__.'/../../config/moip.php';

        // if ($this->isLumen()) {
        //     $this->app->configure('moip');
        // } else {
        //     $this->publishes([$config_file => config_path('moip.php')]);
        // }

        // $this->client = new Client();
        //$this->headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
        //$this->url = (env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/')

        // $this->mergeConfigFrom($config_file, 'moip');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('moip', Moip::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['moip'];
    }

    /**
     * @return bool
     */
    private function isLumen()
    {
        return true === str_contains($this->app->version(), 'Lumen');
    }
}
