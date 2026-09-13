<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Persona\Models\Contact;

class ContactsList extends Component
{
    /**
     * @param  Collection<int, Contact>  $contacts
     */
    public function __construct(
        public Collection $contacts,
    ) {}

    public function render(): View
    {
        return view('persona-blade::components.contacts-list');
    }
}