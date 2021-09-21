<?php

namespace IDM\LaravelHtmlValidator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            HtmlValidatorInterface::class,
            HtmlValidator::class
        );
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'htmlvalidator');
        $this->publishes([__DIR__ . '/config.php' => config_path('htmlvalidator.php')], 'htmlvalidator-config');
    }
}
