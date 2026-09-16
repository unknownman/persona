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

        $this->publishes([
            __DIR__ . '/../../routes/persona.php' => base_path('routes/persona.php'),
        ], 'persona-inertia-routes');

        $this->publishes([
            __DIR__ . '/../../resources/js/pages' => resource_path('js/pages/persona'),
        ], 'persona-inertia-page');
    }
}