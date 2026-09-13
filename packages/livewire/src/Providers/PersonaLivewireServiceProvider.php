<?php

namespace Persona\Livewire\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Persona\Livewire\Http\Livewire\ContactManager;
use Persona\Livewire\Http\Livewire\ProfileOverview;

class PersonaLivewireServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'persona-livewire');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/persona-livewire'),
        ], 'persona-livewire-views');

        Livewire::component('persona.contact-manager', ContactManager::class);
        Livewire::component('persona.profile-overview', ProfileOverview::class);
    }
}