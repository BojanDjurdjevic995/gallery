<?php
namespace Baki\Gallery;
use Illuminate\Support\ServiceProvider;
class GalleryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
    public function provides()
    {
        return ['StoreGallery'];
    }
}
