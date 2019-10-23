<?php 

namespace Eelcol\LaravelMakeFacade;

use Eelcol\LaravelMakeFacade\Console\Commands\MakeFacade;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MakeFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // first register the command(s)
        $this->commands([
            MakeFacade::class
        ]);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        

    }
}
