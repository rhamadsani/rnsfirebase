<?php 

namespace Rnsfirebase;

use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/rnsfirebase.php' => config_path('rnsfirebase.php'),
        ]);
    }

    public function register()
    {

    }
}