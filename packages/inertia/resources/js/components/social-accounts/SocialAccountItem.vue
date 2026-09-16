<script setup lang="ts">
/**
 * SocialAccountItem - a single, atomic social account row.
 * Pure: it never touches the network; it forwards intents to the parent
 * via `primary` / `delete` events.
 */
import type { PersonaId, SocialAccount } from '../../types/persona';

const { account, busy } = defineProps<{
  account: SocialAccount;
  /** Disables the action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  primary: [accountId: PersonaId];
  delete: [accountId: PersonaId];
}>();

function emitPrimary(): void {
  emit('primary', account.id);
}

function emitDelete(): void {
  emit('delete', account.id);
}
</script>

<template>
  <article class="persona-social-account" :data-testid="`persona-social-account-${account.id}`">
    <div class="persona-social-account__main">
      <span class="persona-social-account__platform">{{ account.platform }}</span>
      <span v-if="account.url" class="persona-social-account__username">
        <a :href="account.url" target="_blank" rel="noopener noreferrer">
          {{ account.username }}
        </a>
      </span>
      <span v-else class="persona-social-account__username">
        {{ account.username }}
      </span>
      <span v-if="account.is_primary" class="persona-badge" data-variant="primary">
        primary
      </span>
    </div>

    <div class="persona-social-account__actions">
      <button
        v-if="!account.is_primary"
        type="button"
        class="persona-social-account__button"
        :disabled="busy"
        @click="emitPrimary"
      >
        Make primary
      </button>
      <button
        type="button"
        class="persona-social-account__button persona-social-account__button--danger"
        :disabled="busy"
        @click="emitDelete"
      >
        Remove
      </button>
    </div>
  </article>
</template>