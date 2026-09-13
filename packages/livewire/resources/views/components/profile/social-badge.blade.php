@props(['account' => null, 'activities' => []])
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

    @if($activities)
        <ul class="persona-social-badge__feed" aria-label="Recent activity">
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
                                View post
                            @endif
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</article>