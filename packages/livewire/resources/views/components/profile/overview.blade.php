{{-- $profile, $contacts, $addresses (grouped by type), $documents,
     $socialAccounts, $socialActivities (keyed by account id),
     $relationships, $physicalAttribute, $legalDetail, $initials --}}
<section class="persona-profile" aria-label="{{ __('Profile overview') }}">
    @if($profile)
        <x-persona-livewire::profile.header :profile="$profile" :initials="$initials" />
    @endif

    @if($contacts->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Contacts') }}">
            <h3 class="persona-profile__heading">{{ __('Contacts') }}</h3>
            <div class="persona-profile__contacts">
                @foreach($contacts as $contact)
                    <x-persona-livewire::profile.contact-card :contact="$contact" />
                @endforeach
            </div>
        </section>
    @endif

    @if($addresses->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Addresses') }}">
            <h3 class="persona-profile__heading">{{ __('Addresses') }}</h3>
            @foreach($addresses as $type => $group)
                <div class="persona-profile__address-group">
                    <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                    @foreach($group as $address)
                        <x-persona-livewire::profile.address-card :address="$address" />
                    @endforeach
                </div>
            @endforeach
        </section>
    @endif

    @if($documents->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Documents') }}">
            <h3 class="persona-profile__heading">{{ __('Documents') }}</h3>
            <div class="persona-profile__documents">
                @foreach($documents as $document)
                    <x-persona-livewire::profile.document-item :document="$document" />
                @endforeach
            </div>
        </section>
    @endif

    @if($socialAccounts->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Social accounts') }}">
            <h3 class="persona-profile__heading">{{ __('Social accounts') }}</h3>
            <div class="persona-profile__socials">
                @foreach($socialAccounts as $account)
                    <x-persona-livewire::profile.social-badge
                        :account="$account"
                        :activities="$socialActivities[$account->getKey()] ?? []"
                    />
                @endforeach
            </div>
        </section>
    @endif

    @if($relationships->isNotEmpty())
        <section class="persona-profile__section" aria-label="{{ __('Relationships') }}">
            <h3 class="persona-profile__heading">{{ __('Relationships') }}</h3>
            <div class="persona-profile__relationships">
                @foreach($relationships as $relationship)
                    @php
                        $isSelf  = $relationship->personable_type === $personable->getMorphClass()
                                && (string) $relationship->personable_id === (string) $personable->getKey();
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

    @if($physicalAttribute)
        @php
            $physicalItems = [
                __('Height')     => $physicalAttribute->height ? $physicalAttribute->height . ' cm' : null,
                __('Weight')     => $physicalAttribute->weight ? $physicalAttribute->weight . ' kg' : null,
                __('Eye color')  => $physicalAttribute->eye_color,
                __('Hair color') => $physicalAttribute->hair_color,
                __('Blood type') => $physicalAttribute->blood_type,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="{{ __('Physical attributes') }}">
            <h3 class="persona-profile__heading">{{ __('Physical attributes') }}</h3>
            <x-persona-livewire::profile.attributes-table :items="$physicalItems" />
        </section>
    @endif

    @if($legalDetail)
        @php
            $legalItems = [
                __('Nationality')    => $legalDetail->nationality,
                __('Marital status') => $legalDetail->marital_status,
                __('Tax ID')         => $legalDetail->tax_id,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="{{ __('Legal details') }}">
            <h3 class="persona-profile__heading">{{ __('Legal details') }}</h3>
            <x-persona-livewire::profile.attributes-table :items="$legalItems" />
        </section>
    @endif
</section>
