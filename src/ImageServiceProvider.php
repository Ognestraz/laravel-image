<?php
namespace Ognestraz\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    protected static $aliasesRegistered = false;
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!static::$aliasesRegistered) {
            static::$aliasesRegistered = true;
            class_alias('Intervention\Image\Facades\Image', 'Img');
        }
        
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/app/Http/routes.php';
        }
        
        $this->app->register('Intervention\Image\ImageServiceProvider');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
