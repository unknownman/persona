{{-- Single social account badge. Variables: $account, $activities (array). --}}
@php
    $handle = \Illuminate\Support\Str::startsWith($account->username, '@')
        ? $account->username
        : '@' . $account->username;
@endphp
<article class="persona-social-badge" aria-label="{{ ucfirst($account->platform) }} account">
    <div class="persona-social-badge__main">
        <span class="persona-social-badge__platform">{{ ucfirst($account->platform) }}</span>
        @if($account->url)
            <a
                href="{{ $account->url }}"
                class="persona-social-badge__handle"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="{{ __('Profile on :platform', ['platform' => $account->platform]) }}"
            >
                {{ $handle }}
            </a>
        @else
            <span class="persona-social-badge__handle">{{ $handle }}</span>
        @endif
        @if($account->is_primary)
            <span class="persona-social-badge__primary" aria-label="{{ __('Primary account') }}">{{ __('Primary') }}</span>
        @endif
    </div>

    <div class="persona-social-badge__feed">
        @if($activities)
            <ul class="persona-social-badge__feed-list" aria-label="{{ __('Recent activity') }}">
                @foreach($activities as $activity)
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
                                    {{ __('View post') }}
                                @endif
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="persona-social-badge__no-activity">{{ __('No recent activity.') }}</p>
        @endif
    </div>
</article>