<?php

namespace Persona\Blade\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Persona\Blade\View\Components\AddressesList;
use Persona\Blade\View\Components\ContactsList;
use Persona\Blade\View\Components\ProfileForm;
use Persona\Blade\View\Components\ProfileOverview;

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
        Blade::component(ProfileOverview::class, 'persona-blade-profile-overview');
        Blade::component(ContactsList::class, 'persona-contacts-list');
        Blade::component(AddressesList::class, 'persona-addresses-list');
    }
}