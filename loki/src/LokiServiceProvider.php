<?php

namespace Williamtome\Loki;

use Illuminate\Support\ServiceProvider;

class LokiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'loki');
    }

    public function register()
    {
        //
    }
}
