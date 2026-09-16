<script setup lang="ts">
/**
 * SocialAccountList - iterates an array of social accounts and renders a
 * SocialAccountItem per row, forwarding the `set-primary` / `delete` intents
 * up to its parent. Pure: takes accounts as a prop and never mutates state.
 */
import SocialAccountItem from './SocialAccountItem.vue';
import type { PersonaId, SocialAccount } from '../../types/persona';

defineProps<{
  accounts: SocialAccount[];
  /** Disables every item's action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  'set-primary': [accountId: PersonaId];
  delete: [accountId: PersonaId];
}>();

function forwardPrimary(accountId: PersonaId): void {
  emit('set-primary', accountId);
}

function forwardDelete(accountId: PersonaId): void {
  emit('delete', accountId);
}
</script>

<template>
  <ul class="persona-social-list">
    <li
      v-for="account in accounts"
      :key="account.id"
      class="persona-social-list__item"
    >
      <SocialAccountItem
        :account="account"
        :busy="busy"
        @primary="forwardPrimary"
        @delete="forwardDelete"
      />
    </li>
    <li v-if="accounts.length === 0" class="persona-social-list__empty">
      No social accounts yet.
    </li>
  </ul>
</template>