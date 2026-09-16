{{-- Consumes the ProfileOverview component's computed properties:
     $this->footprint (profile, contacts, addresses, documents,
     socialAccounts, relationships, physicalAttribute, legalDetail),
     $this->socialActivities (keyed by account id), $this->addressGroups,
     $this->physicalItems, $this->legalItems, $this->relationshipCounterparts
     and $this->initials.

     Every section stays visible when empty and falls back to a shared
     empty-state, so the page reads well whether the footprint is bare
     or fully populated. Each section is a small dedicated Blade partial
     a host can override independently. --}}
@php
    $footprint        = $this->footprint;
    $socialActivities = $this->socialActivities;
    $counterparts     = $this->relationshipCounterparts;
@endphp
<section class="persona-profile" aria-label="{{ __('Profile overview') }}">
    @if($footprint['profile'])
        <x-persona-livewire::profile.header :profile="$footprint['profile']" :initials="$this->initials" />
    @else
        <x-persona-livewire::profile.empty-state
            variant="page"
            :title="__('No profile yet')"
            :text="__('This entity has not added a profile yet.')"
        />
    @endif

    <section class="persona-profile__section" aria-label="{{ __('Contacts') }}">
        <h3 class="persona-profile__heading">{{ __('Contacts') }}</h3>
        <div class="persona-profile__contacts">
            @forelse($footprint['contacts'] as $contact)
                <x-persona-livewire::profile.contact-card :contact="$contact" />
            @empty
                <x-persona-livewire::profile.empty-state
                    :title="__('No contacts yet')"
                    :text="__('Add an email or phone number when available.')"
                />
            @endforelse
        </div>
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Addresses') }}">
        <h3 class="persona-profile__heading">{{ __('Addresses') }}</h3>
        @if($this->addressGroups->isNotEmpty())
            <div class="persona-profile__addresses">
                @foreach($this->addressGroups as $type => $group)
                    <div class="persona-profile__address-group">
                        <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                        <div class="persona-profile__address-items">
                            @foreach($group as $address)
                                <x-persona-livewire::profile.address-card :address="$address" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-persona-livewire::profile.empty-state
                :title="__('No addresses yet')"
                :text="__('Add a residence, shipping, or billing address when available.')"
            />
        @endif
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Documents') }}">
        <h3 class="persona-profile__heading">{{ __('Documents') }}</h3>
        <div class="persona-profile__documents">
            @forelse($footprint['documents'] as $document)
                <x-persona-livewire::profile.document-item :document="$document" />
            @empty
                <x-persona-livewire::profile.empty-state
                    :title="__('No documents yet')"
                    :text="__('Verified identity documents will show up here.')"
                />
            @endforelse
        </div>
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Social accounts') }}">
        <h3 class="persona-profile__heading">{{ __('Social accounts') }}</h3>
        <div class="persona-profile__socials">
            @forelse($footprint['socialAccounts'] as $account)
                <x-persona-livewire::profile.social-badge
                    :account="$account"
                    :activities="$socialActivities[$account->getKey()] ?? []"
                />
            @empty
                <x-persona-livewire::profile.empty-state
                    :title="__('No social accounts yet')"
                    :text="__('Connected social profiles will appear here.')"
                />
            @endforelse
        </div>
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Relationships') }}">
        <h3 class="persona-profile__heading">{{ __('Relationships') }}</h3>
        <div class="persona-profile__relationships">
            @forelse($footprint['relationships'] as $relationship)
                @php
                    $entry = $counterparts[$relationship->getKey()] ?? null;
                @endphp
                @if($entry && $entry['counterpart'])
                    <x-persona-livewire::profile.relationship-chip
                        :relationship="$relationship"
                        :counterpart="$entry['counterpart']"
                        :incoming="$entry['self']"
                    />
                @endif
            @empty
                <x-persona-livewire::profile.empty-state
                    :title="__('No relationships yet')"
                    :text="__('Linked people and organizations will appear here.')"
                />
            @endforelse
        </div>
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Physical attributes') }}">
        <h3 class="persona-profile__heading">{{ __('Physical attributes') }}</h3>
        @if($this->physicalItems)
            <x-persona-livewire::profile.attributes-table :items="$this->physicalItems" />
        @else
            <x-persona-livewire::profile.empty-state
                :title="__('No physical attributes')"
                :text="__('Height, weight, and other attributes will show up here.')"
            />
        @endif
    </section>

    <section class="persona-profile__section" aria-label="{{ __('Legal details') }}">
        <h3 class="persona-profile__heading">{{ __('Legal details') }}</h3>
        @if($this->legalItems)
            <x-persona-livewire::profile.attributes-table :items="$this->legalItems" />
        @else
            <x-persona-livewire::profile.empty-state
                :title="__('No legal details')"
                :text="__('Nationality, marital status, and tax details appear here.')"
            />
        @endif
    </section>
</section>