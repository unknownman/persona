<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Persona\Models\Address;

class AddressesList extends Component
{
    /**
     * @param  Collection<int, Address>  $addresses
     */
    public function __construct(
        public Collection $addresses,
    ) {}

    public function render(): View
    {
        return view('persona-blade::components.addresses-list');
    }
}