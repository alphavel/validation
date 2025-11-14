<?php

namespace Alphavel\Validation;

use Alphavel\Framework\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('validator', function () {
            return new Validator();
        });
    }

    public function boot(): void
    {
        //
    }
}
