<script setup lang="ts">
/**
 * SocialActivityCard - a connected account with its recent activity feed.
 *
 * The feed data arrives pre-resolved from the backend resolver; the UI
 * never calls third-party APIs directly. With the default Null resolver
 * the feed simply collapses and the card shows platform + handle only.
 */
import { computed } from 'vue';
import type { SocialAccount, SocialActivity } from '../../types/persona';

const { account, activity } = defineProps<{
  account: SocialAccount;
  activity: SocialActivity[];
}>();

const accountHref = computed(() => account.url ?? undefined);
const handle = computed(() => account.username.replace(/^@/, ''));

function publishedLabel(publishedAt: string | undefined): string {
  if (!publishedAt) return 'View post';
  return `Posted ${new Date(publishedAt).toLocaleDateString()}`;
}
</script>

<template>
  <article class="persona-social-badge">
    <div class="persona-social-badge__main">
      <span class="persona-social-badge__platform">
        {{ account.platform.charAt(0).toUpperCase() + account.platform.slice(1) }}
      </span>
      <a
        :href="accountHref"
        class="persona-social-badge__handle"
        target="_blank"
        rel="noopener noreferrer"
        :aria-label="`Profile on ${account.platform}`"
      >
        @{{ handle }}
      </a>
      <span
        v-if="account.is_primary"
        class="persona-social-badge__primary"
        aria-label="Primary account"
      >
        Primary
      </span>
    </div>

    <ul v-if="activity.length" class="persona-social-badge__feed" aria-label="Recent activity">
      <li
        v-for="(item, index) in activity"
        :key="index"
        class="persona-social-badge__feed-item"
      >
        <p v-if="item.text" class="persona-social-badge__feed-text">{{ item.text }}</p>
        <a
          v-if="item.url"
          :href="item.url"
          class="persona-social-badge__feed-link"
          target="_blank"
          rel="noopener noreferrer"
        >
          {{ publishedLabel(item.published_at) }}
        </a>
      </li>
    </ul>
  </article>
</template>