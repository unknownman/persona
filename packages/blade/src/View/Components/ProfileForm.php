<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Persona\Models\Profile;

class ProfileForm extends Component
{
    public function __construct(
        public string $action,
        public string $method = 'POST',
        public ?Profile $profile = null,
    ) {}

    public function render(): View
    {
        return view('persona-blade::components.profile-form');
    }
}