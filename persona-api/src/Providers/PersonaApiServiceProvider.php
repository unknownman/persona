<?php

namespace Persona\Api\Providers;

use Illuminate\Support\ServiceProvider;

class PersonaApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../stubs/Controllers/PersonaApiController.php.stub' => app_path('Http/Controllers/PersonaApiController.php.stub'),
        ], 'persona-api-stubs');
    }
}