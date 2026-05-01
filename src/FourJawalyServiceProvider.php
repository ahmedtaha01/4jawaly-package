<?php

namespace AhmedTaha\FourjawalyPackage;

use Illuminate\Support\ServiceProvider;

class FourJawalyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fourjawaly.php',
            'fourjawaly'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fourjawaly.php' => config_path('fourjawaly.php'),
        ], 'fourjawaly-config');
    }
}
