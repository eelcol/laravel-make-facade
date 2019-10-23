<?php

namespace Eelcol\LaravelMakeFacade\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFacade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:facade {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new facade file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
        * First check if the facades folder exists
        */
        $directory = app_path("Facades");

        if(!File::exists($directory))
        {
            File::makeDirectory($directory);
        }

        /**
        * Add FacadesServiceProvider
        */
        if(!File::exists(app_path("Providers/FacadeServiceProvider.php")))
        {
            $this->createFacadeServiceProvider();
        }

        /**
        * Now create the facade file
        */
        $stub = $this->loadStub(__DIR__."/../../Stubs/Facade.stub");

        /**
        * Now create variable names
        */
        $vars = [];
        $vars['CLASSNAME_CAMELCASE'] = Str::camel($this->argument('name'));
        $vars['CLASSNAME'] = ucfirst($vars['CLASSNAME_CAMELCASE']);
        $filename = $vars['CLASSNAME'] . ".php";

        $stub = str_replace(array_keys($vars), array_values($vars), $stub);

        /**
        * Save stub to folder
        */
        $path = $directory . "/" . $filename;
        File::put($path, $stub);

        /**
        * Now update FacadeServiceProvider
        */
        $stub = $this->loadStub(__DIR__."/../../Stubs/ServiceProviderSnippet.stub");
        $stub = str_replace(array_keys($vars), array_values($vars), $stub);

        $this->insertStubIntoAppServiceProvider($stub);

        $this->info("All files are generated. Please check FacadeServiceProvider.php if the facade returns the correct class.");
    }

    /**
    * @method createFacadeServiceProvider
    */
    private function createFacadeServiceProvider()
    {
        File::copy(__DIR__."/../../Stubs/FacadeServiceProvider.stub", app_path("Providers/FacadeServiceProvider.php"));

        $this->info("Add 'App\Providers\FacadeServiceProvider::class' to your providers in config/app.php");
    }

    /**
    * @method loadStub
    * @param $path | string
    * @return string
    */
    private function loadStub($path)
    {
        return File::get($path);
    }

    /**
    * @method insertStubIntoAppServiceProvider
    * @param $stub | string 
    * Adds a string to the register method of the AppServiceProvider
    */
    private function insertStubIntoAppServiceProvider($stub)
    {
        $FacadeServiceProvider = File::get(app_path("Providers/FacadeServiceProvider.php"));

        if(strpos($FacadeServiceProvider, "// Insert new facades below") === -1)
        {
            $this->error("Cannot automaticly update FacadeServiceProvider.php. Please register this facade yourself.");
            return;
        }

        $FacadeServiceProvider = str_replace("// Insert new facades below", "// Insert new facades below\n\n" . $stub . "\n", $FacadeServiceProvider);        
        
        File::put(app_path("Providers/FacadeServiceProvider.php"), $FacadeServiceProvider);
    }

}
