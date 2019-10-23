# Laravel Make Facade Command

Facades can be extremely helpful in Laravel. This package adds a simple command to the CLI to add an easy way to create a new facade.

# Example

Use the following command to create a new facade:

````
php artisan make:facade NameOfFacade
````

This command creates a file in the folder `app/Facades`. It also creates a new `FacadeServiceProvider.php` file and updates this file everytime a new Facade is created.

After creating the first facade, this FacadeServiceProvider should be added to the `providers` array in `config/app.php`.

If you want to create a facade called 'Facebook', run the following command:

````
php artisan make:facade Facebook
````

This command will perform the following tasks:
- create the file app/Facades/Facebook.php
- create the file app/Providers/FacadeServiceProvider.php if this file does not exists yet
- adds an entry to the FacadeServiceProvider for this facade

You still have to add the FacadeServiceProvider to `config/app.php` and check if the facade returns the correct class.

# Installation

Require this package with composer.

````
composer require eelcol/laravel-make-facade
````

Laravel 5.5 and up uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

````
Eelcol\LaravelMakeFacade\MakeFacadeServiceProvider::class,
````