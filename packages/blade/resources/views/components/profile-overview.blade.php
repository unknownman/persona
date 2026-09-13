{{-- Full read-only profile overview for non-Livewire Blade hosts.
     Props: $personable (Model), $profile, $contacts, $addresses (Collection),
     $documents, $socialAccounts, $relationships, $physicalAttribute,
     $legalDetail, $socialActivities (keyed by account id), $initials --}}
<section class="persona-profile" aria-label="Profile overview">
    @if($profile)
        @php
            $fullName = collect([
                $profile->first_name,
                $profile->middle_name,
                $profile->last_name,
            ])->filter()->implode(' ');
        @endphp
        <header class="persona-profile__header">
            <span class="persona-profile__avatar" aria-hidden="true">
                <span class="persona-profile__avatar-initials">{{ $initials }}</span>
            </span>

            <div class="persona-profile__identity">
                <h2 class="persona-profile__name">{{ $fullName ?: 'Unnamed' }}</h2>

                <div class="persona-profile__chips" aria-label="Profile details">
                    @if($profile->timezone)
                        <span class="persona-profile__chip">{{ $profile->timezone }}</span>
                    @endif
                    @if($profile->locale)
                        <span class="persona-profile__chip">{{ $profile->locale }}</span>
                    @endif
                    @if($profile->gender)
                        <span class="persona-profile__chip">{{ ucfirst(str_replace('_', ' ', $profile->gender)) }}</span>
                    @endif
                    @if($profile->birth_date)
                        <span class="persona-profile__chip">{{ $profile->birth_date->format('M j, Y') }}</span>
                    @endif
                </div>
            </div>
        </header>
    @endif

    @if($contacts->isNotEmpty())
        <section class="persona-profile__section" aria-label="Contacts">
            <h3 class="persona-profile__heading">Contacts</h3>
            <div class="persona-profile__contacts">
                @foreach($contacts as $contact)
                    <article class="persona-contact-card">
                        <div class="persona-contact-card__main">
                            <span class="persona-contact-card__value">{{ $contact->value }}</span>
                            <span class="persona-contact-card__type">{{ $contact->type }}</span>

                            <span class="persona-contact-card__badges" aria-label="Status">
                                @if($contact->is_primary)
                                    <span class="persona-contact-badge persona-contact-badge--primary" aria-label="Primary contact">Primary</span>
                                @endif
                                @if($contact->is_verified)
                                    <span class="persona-contact-badge persona-contact-badge--verified" aria-label="Verified">Verified</span>
                                @endif
                                @if($contact->is_emergency)
                                    <span class="persona-contact-badge persona-contact-badge--emergency" aria-label="Emergency contact">Emergency</span>
                                @endif
                            </span>
                        </div>

                        <nav class="persona-contact-card__actions" aria-label="Contact actions">
                            @if($contact->type === 'email')
                                <a href="mailto:{{ $contact->value }}" class="persona-contact-card__action">Email</a>
                            @endif
                            @if($contact->type === 'phone')
                                <a href="tel:{{ $contact->value }}" class="persona-contact-card__action">Call</a>
                                <a href="sms:{{ $contact->value }}" class="persona-contact-card__action">SMS</a>
                            @endif
                        </nav>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($addresses->isNotEmpty())
        <section class="persona-profile__section" aria-label="Addresses">
            <h3 class="persona-profile__heading">Addresses</h3>
            @foreach($addresses->groupBy('type') as $type => $group)
                <div class="persona-profile__address-group">
                    <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                    @foreach($group as $address)
                        <article class="persona-address-card">
                            <div class="persona-address-card__main">
                                <span class="persona-address-card__line1">{{ $address->line_1 }}</span>
                                @if($address->line_2)
                                    <span class="persona-address-card__line2">{{ $address->line_2 }}</span>
                                @endif
                                @php
                                    $locality = collect([
                                        $address->city,
                                        $address->state,
                                        $address->country_code,
                                        $address->zip_code,
                                    ])->filter()->implode(', ');
                                @endphp
                                @if($locality)
                                    <span class="persona-address-card__locality">{{ $locality }}</span>
                                @endif
                            </div>

                            @if($address->is_primary)
                                <span class="persona-address-badge persona-address-badge--primary" aria-label="Primary address">Primary</span>
                            @endif
                        </article>
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
                    <article class="persona-document-item">
                        <div class="persona-document-item__main">
                            <span class="persona-document-item__type">{{ ucwords(str_replace('_', ' ', $document->type)) }}</span>
                            <span class="persona-document-item__number">
                                @if($document->number)
                                    &bull;&bull;&bull;&bull; {{ substr($document->number, -4) }}
                                @else
                                    &mdash;
                                @endif
                            </span>
                            @if($document->expires_at)
                                <span
                                    class="persona-document-item__expiry @if($document->isExpired()) persona-document-item__expiry--expired @endif"
                                    aria-label="{{ $document->isExpired() ? 'Expired' : 'Expires' }} {{ $document->expires_at->format('M j, Y') }}"
                                >
                                    {{ $document->isExpired() ? 'Expired' : 'Expires' }} {{ $document->expires_at->format('M j, Y') }}
                                </span>
                            @endif
                        </div>

                        <span
                            class="persona-document-status persona-document-status--{{ $document->status }}"
                            aria-label="Status: {{ $document->status }}"
                        >
                            {{ ucfirst($document->status) }}
                        </span>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($socialAccounts->isNotEmpty())
        <section class="persona-profile__section" aria-label="Social accounts">
            <h3 class="persona-profile__heading">Social accounts</h3>
            <div class="persona-profile__socials">
                @foreach($socialAccounts as $account)
                    <article class="persona-social-badge">
                        <div class="persona-social-badge__main">
                            <span class="persona-social-badge__platform">{{ ucfirst($account->platform) }}</span>
                            <a
                                href="{{ $account->url }}"
                                class="persona-social-badge__handle"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Profile on {{ $account->platform }}"
                            >
                                {{ \Illuminate\Support\Str::startsWith($account->username, '@') ? $account->username : '@' . $account->username }}
                            </a>
                            @if($account->is_primary)
                                <span class="persona-social-badge__primary" aria-label="Primary account">Primary</span>
                            @endif
                        </div>

                        @if($socialActivities[$account->getKey()] ?? null)
                            <ul class="persona-social-badge__feed" aria-label="Recent activity">
                                @foreach($socialActivities[$account->getKey()] as $activity)
                                    <li class="persona-social-badge__feed-item">
                                        @if($activity['text'] ?? null)
                                            <p class="persona-social-badge__feed-text">{{ $activity['text'] }}</p>
                                        @endif
                                        @if($activity['url'] ?? null)
                                            <a
                                                href="{{ $activity['url'] }}"
                                                class="persona-social-badge__feed-link"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                @if($activity['published_at'] ?? null)
                                                    {{ \Illuminate\Support\Carbon::parse($activity['published_at'])->diffForHumans() }}
                                                @else
                                                    View post
                                                @endif
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
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
                        <div class="persona-relationship-chip" aria-label="Relationship: {{ $relationship->type }}">
                            <span class="persona-relationship-chip__type">{{ ucfirst($relationship->type) }}</span>
                            <span class="persona-relationship-chip__name">
                                @if(method_exists($counterpart, 'persona') && $counterpart->profile)
                                    {{ trim(collect([
                                        $counterpart->profile->first_name,
                                        $counterpart->profile->last_name,
                                    ])->filter()->implode(' ')) ?: 'Unnamed' }}
                                @else
                                    {{ class_basename($counterpart) }} #{{ $counterpart->getKey() }}
                                @endif
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if($physicalAttribute)
        @php
            $physicalItems = [
                'Height'     => $physicalAttribute->height ? $physicalAttribute->height . ' cm' : null,
                'Weight'     => $physicalAttribute->weight ? $physicalAttribute->weight . ' kg' : null,
                'Eye color'  => $physicalAttribute->eye_color,
                'Hair color' => $physicalAttribute->hair_color,
                'Blood type' => $physicalAttribute->blood_type,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="Physical attributes">
            <h3 class="persona-profile__heading">Physical attributes</h3>
            <dl class="persona-attributes-table">
                @foreach($physicalItems as $label => $value)
                    @if($value)
                        <div class="persona-attributes-table__row">
                            <dt class="persona-attributes-table__key">{{ $label }}</dt>
                            <dd class="persona-attributes-table__value">{{ $value }}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        </section>
    @endif

    @if($legalDetail)
        @php
            $legalItems = [
                'Nationality'    => $legalDetail->nationality,
                'Marital status' => $legalDetail->marital_status,
                'Tax ID'         => $legalDetail->tax_id,
            ];
        @endphp
        <section class="persona-profile__section" aria-label="Legal details">
            <h3 class="persona-profile__heading">Legal details</h3>
            <dl class="persona-attributes-table">
                @foreach($legalItems as $label => $value)
                    @if($value)
                        <div class="persona-attributes-table__row">
                            <dt class="persona-attributes-table__key">{{ $label }}</dt>
                            <dd class="persona-attributes-table__value">{{ $value }}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        </section>
    @endif
</section>
