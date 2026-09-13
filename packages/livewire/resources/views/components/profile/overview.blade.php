{{-- $profile, $contacts, $addresses (grouped by type), $documents,
     $socialAccounts, $socialActivities (keyed by account id),
     $relationships, $physicalAttribute, $legalDetail, $initials --}}
<section class="persona-profile" aria-label="Profile overview">
    @if($profile)
        @include('persona-livewire::components.profile.header', [
            'profile'    => $profile,
            'initials'   => $initials,
        ])
    @endif

    @if($contacts->isNotEmpty())
        <section class="persona-profile__section" aria-label="Contacts">
            <h3 class="persona-profile__heading">Contacts</h3>
            <div class="persona-profile__contacts">
                @foreach($contacts as $contact)
                    @include('persona-livewire::components.profile.contact-card', ['contact' => $contact])
                @endforeach
            </div>
        </section>
    @endif

    @if($addresses->isNotEmpty())
        <section class="persona-profile__section" aria-label="Addresses">
            <h3 class="persona-profile__heading">Addresses</h3>
            @foreach($addresses as $type => $group)
                <div class="persona-profile__address-group">
                    <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                    @foreach($group as $address)
                        @include('persona-livewire::components.profile.address-card', ['address' => $address])
                    @endforeach
                </div>
            @endforeach
        </section>
    @endif

    @if($documents->isNotEmpty())
        <section class="persona-profile__section" aria-label="Documents">
            <h3 class="persona-profile__heading">Documents</h3>
            <div class="persona-profile__documents">
                @foreach($documents as $document)
                    @include('persona-livewire::components.profile.document-item', ['document' => $document])
                @endforeach
            </div>
        </section>
    @endif

    @if($socialAccounts->isNotEmpty())
        <section class="persona-profile__section" aria-label="Social accounts">
            <h3 class="persona-profile__heading">Social accounts</h3>
            <div class="persona-profile__socials">
                @foreach($socialAccounts as $account)
                    @include('persona-livewire::components.profile.social-badge', [
                        'account'    => $account,
                        'activities' => $socialActivities[$account->getKey()] ?? [],
                    ])
                @endforeach
            </div>
        </section>
    @endif

    @if($relationships->isNotEmpty())
        <section class="persona-profile__section" aria-label="Relationships">
            <h3 class="persona-profile__heading">Relationships</h3>
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
                        @include('persona-livewire::components.profile.relationship-chip', [
                            'relationship' => $relationship,
                            'counterpart'  => $counterpart,
                        ])
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if($physicalAttribute)
        <section class="persona-profile__section" aria-label="Physical attributes">
            <h3 class="persona-profile__heading">Physical attributes</h3>
            @include('persona-livewire::components.profile.attributes-table', [
                'items' => [
                    'Height'     => $physicalAttribute->height ? $physicalAttribute->height . ' cm' : null,
                    'Weight'     => $physicalAttribute->weight ? $physicalAttribute->weight . ' kg' : null,
                    'Eye color'  => $physicalAttribute->eye_color,
                    'Hair color' => $physicalAttribute->hair_color,
                    'Blood type' => $physicalAttribute->blood_type,
                ],
            ])
        </section>
    @endif

    @if($legalDetail)
        <section class="persona-profile__section" aria-label="Legal details">
            <h3 class="persona-profile__heading">Legal details</h3>
            @include('persona-livewire::components.profile.attributes-table', [
                'items' => [
                    'Nationality'    => $legalDetail->nationality,
                    'Marital status' => $legalDetail->marital_status,
                    'Tax ID'         => $legalDetail->tax_id,
                ],
            ])
        </section>
    @endif
</section>