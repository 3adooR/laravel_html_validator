<?php

namespace IDM\LaravelHtmlValidator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/web.php');
    }
}