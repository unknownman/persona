{{-- Profile identity header. Variables: $profile, $fullName, $initials. --}}
<header class="persona-profile__header">
    <span class="persona-profile__avatar" aria-hidden="true">
        @php
            $avatarUrl = $profile?->avatar_url;
        @endphp
        @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="Avatar" class="persona-profile__avatar-img">
        @else
            <span class="persona-profile__avatar-initials">{{ $initials }}</span>
        @endif
    </span>

    <div class="persona-profile__identity">
        <h2 class="persona-profile__name">{{ $fullName ?: __('Unnamed') }}</h2>

        <div class="persona-profile__chips" aria-label="{{ __('Profile details') }}">
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