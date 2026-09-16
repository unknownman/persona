<script setup lang="ts">
/**
 * PersonaSocialAccountManager - thin orchestration layer for the social
 * accounts slice.
 *
 * Composes the presentational atoms (SocialAccountList, SocialAccountForm)
 * with the useSocialAccounts composable and stays out of the way: all state
 * is either passed in via props or held by the composable. On any successful
 * mutation it emits `updated` so the host page can refetch props.
 */
import { useSocialAccounts } from '../../composables/useSocialAccounts';
import SocialAccountList from './SocialAccountList.vue';
import SocialAccountForm from './SocialAccountForm.vue';
import type { CreateSocialAccountInput, PersonaId, SocialAccount } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  accounts: SocialAccount[];
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

const emit = defineEmits<{
  updated: [];
}>();

const {
  isLoading,
  errors,
  addSocialAccount,
  makePrimary,
  deleteSocialAccount,
} = useSocialAccounts(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleAddAccount(payload: CreateSocialAccountInput): void {
  addSocialAccount(payload);
}

function handleSetPrimary(accountId: PersonaId): void {
  makePrimary(accountId);
}

function handleDeleteAccount(accountId: PersonaId): void {
  deleteSocialAccount(accountId);
}
</script>

<template>
  <section class="persona-socials-manager" data-testid="persona-socials-manager">
    <SocialAccountList
      :accounts="accounts"
      :busy="isLoading"
      @set-primary="handleSetPrimary"
      @delete="handleDeleteAccount"
    />

    <p
      v-for="(message, field) in errors"
      :key="field"
      class="persona-socials-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <SocialAccountForm
      :busy="isLoading"
      @submit="handleAddAccount"
    />
  </section>
</template>