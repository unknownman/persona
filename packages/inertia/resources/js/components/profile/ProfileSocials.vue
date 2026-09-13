<script setup lang="ts">
/**
 * ProfileSocials - connected social accounts section.
 *
 * Activity is resolved by the backend `SocialActivityResolverContract`;
 * this component only renders whatever enrichment it receives.
 */
import SocialActivityCard from './SocialActivityCard.vue';
import type { SocialAccount, SocialActivity } from '../../types/persona';

defineProps<{
  accounts: SocialAccount[];
  activities: Record<string, SocialActivity[]>;
}>();
</script>

<template>
  <section class="persona-profile__section" aria-label="Social accounts">
    <h3 class="persona-profile__heading">Social accounts</h3>
    <div class="persona-profile__socials">
      <SocialActivityCard
        v-for="account in accounts"
        :key="account.id"
        :account="account"
        :activity="activities[String(account.id)] ?? []"
      />
    </div>
  </section>
</template>