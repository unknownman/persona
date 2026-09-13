<?php

namespace Persona\Blade\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Persona\Blade\View\Components\ProfileForm;

class PersonaBladeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'persona-blade');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/persona-blade'),
        ], 'persona-blade-views');

        Blade::component(ProfileForm::class, 'persona-profile-form');
    }
}