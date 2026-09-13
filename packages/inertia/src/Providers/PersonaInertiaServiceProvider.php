<?php

namespace Persona\Inertia\Providers;

use Illuminate\Support\ServiceProvider;

class PersonaInertiaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../resources/js' => resource_path('js/persona'),
        ], 'persona-inertia-frontend');

        $this->publishes([
            __DIR__ . '/../../stubs/Controllers/PersonaController.php.stub' => app_path('Http/Controllers/PersonaController.php'),
        ], 'persona-inertia-controllers');
    }
}