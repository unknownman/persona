{{-- Consumes the ProfileOverview component's computed properties:
     $this->footprint (profile, contacts, addresses, documents,
     socialAccounts, relationships, physicalAttribute, legalDetail),
     $this->socialActivities (keyed by account id) and $this->initials. --}}
@php
    $footprint        = $this->footprint;
    $socialActivities = $this->socialActivities;
@endphp
<section class="persona-profile" aria-label="{{ __('Profile overview') }}">
    @if($footprint['profile'])
        <x-persona-livewire::profile.header :profile="$footprint['profile']" :initials="$this->initials" />
    @endif

    @if($footprint['contacts']->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Contacts') }}">
            <h3 class="persona-profile__heading">{{ __('Contacts') }}</h3>
            <div class="persona-profile__contacts">
                @foreach($footprint['contacts'] as $contact)
                    <x-persona-livewire::profile.contact-card :contact="$contact" />
                @endforeach
            </div>
        </section>
    @endif

    @if($footprint['addresses']->isNotEmpty())
        @php
            $addressGroups = $footprint['addresses']->groupBy('type');
        @endphp
        <section class="persona-profile__section" aria-label="{{ __('Addresses') }}">
            <h3 class="persona-profile__heading">{{ __('Addresses') }}</h3>
            @foreach($addressGroups as $type => $group)
                <div class="persona-profile__address-group">
                    <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                    @foreach($group as $address)
                        <x-persona-livewire::profile.address-card :address="$address" />
                    @endforeach
                </div>
            @endforeach
        </section>
    @endif

    @if($footprint['documents']->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Documents') }}">
            <h3 class="persona-profile__heading">{{ __('Documents') }}</h3>
            <div class="persona-profile__documents">
                @foreach($footprint['documents'] as $document)
                    <x-persona-livewire::profile.document-item :document="$document" />
                @endforeach
            </div>
        </section>
    @endif

    @if($footprint['socialAccounts']->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Social accounts') }}">
            <h3 class="persona-profile__heading">{{ __('Social accounts') }}</h3>
            <div class="persona-profile__socials">
                @foreach($footprint['socialAccounts'] as $account)
                    <x-persona-livewire::profile.social-badge
                        :account="$account"
                        :activities="$socialActivities[$account->getKey()] ?? []"
                    />
                @endforeach
            </div>
        </section>
    @endif

    @if($footprint['relationships']->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Relationships') }}">
            <h3 class="persona-profile__heading">{{ __('Relationships') }}</h3>
            <div class="persona-profile__relationships">
                @foreach($footprint['relationships'] as $relationship)
                    @php
                        $isSelf  = $relationship->personable_type === $this->personable->getMorphClass()
                                && (string) $relationship->personable_id === (string) $this->personable->getKey();
                        $counterpart = $isSelf
                            ? $relationship->relatedPersonable
                            : $relationship->personable;
                    @endphp
                    @if($counterpart)
                        <x-persona-livewire::profile.relationship-chip
                            :relationship="$relationship"
                            :counterpart="$counterpart"
                        />
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if($footprint['physicalAttribute'])
        @php
            $physicalItems = [
                __('Height')     => $footprint['physicalAttribute']->height ? $footprint['physicalAttribute']->height . ' cm' : null,
                __('Weight')     => $footprint['physicalAttribute']->weight ? $footprint['physicalAttribute']->weight . ' kg' : null,
                __('Eye color')  => $footprint['physicalAttribute']->eye_color,
                __('Hair color') => $footprint['physicalAttribute']->hair_color,
                __('Blood type') => $footprint['physicalAttribute']->blood_type,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="{{ __('Physical attributes') }}">
            <h3 class="persona-profile__heading">{{ __('Physical attributes') }}</h3>
            <x-persona-livewire::profile.attributes-table :items="$physicalItems" />
        </section>
    @endif

    @if($footprint['legalDetail'])
        @php
            $legalItems = [
                __('Nationality')    => $footprint['legalDetail']->nationality,
                __('Marital status') => $footprint['legalDetail']->marital_status,
                __('Tax ID')         => $footprint['legalDetail']->tax_id,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="{{ __('Legal details') }}">
            <h3 class="persona-profile__heading">{{ __('Legal details') }}</h3>
            <x-persona-livewire::profile.attributes-table :items="$legalItems" />
        </section>
    @endif
</section>