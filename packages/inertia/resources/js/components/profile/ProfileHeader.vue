<script setup lang="ts">
/**
 * ProfileHeader - identity block: avatar image (when an `avatar_url` is
 * resolved), initials fallback, full name, and timezone/locale/demographic
 * chips.
 *
 * The default schema is initials-based (no avatar column); hosts that
 * resolve an avatar URL via `AvatarResolverContract` get an `<img>` for
 * free — the data contract stays untouched.
 */
import { computed } from 'vue';
import { useProfile } from '../../composables/useProfile';
import type { Profile } from '../../types/persona';

const { profile } = defineProps<{
  profile: Profile;
}>();

const { initials } = useProfile(profile);

const fullName = computed(() =>
  [profile.first_name, profile.middle_name, profile.last_name]
    .filter(Boolean)
    .join(' '),
);

function birthdayLabel(date: string | null): string | null {
  if (!date) return null;
  return new Date(date).toLocaleDateString(undefined, {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
}
</script>

<template>
  <header class="persona-profile__header">
    <span class="persona-profile__avatar" aria-hidden="true">
      <img
        v-if="profile.avatar_url"
        :src="profile.avatar_url"
        alt="Avatar"
        class="persona-profile__avatar-img"
      >
      <span v-else class="persona-profile__avatar-initials">{{ initials }}</span>
    </span>

    <div class="persona-profile__identity">
      <h2 class="persona-profile__name">{{ fullName || 'Unnamed' }}</h2>
      <div class="persona-profile__chips" aria-label="Profile details">
        <span v-if="profile.timezone" class="persona-profile__chip">{{ profile.timezone }}</span>
        <span v-if="profile.locale" class="persona-profile__chip">{{ profile.locale }}</span>
        <span v-if="profile.gender" class="persona-profile__chip">
          {{ profile.gender.replaceAll('_', ' ') }}
        </span>
        <span v-if="profile.birth_date" class="persona-profile__chip">
          {{ birthdayLabel(profile.birth_date) }}
        </span>
      </div>
    </div>
  </header>
</template>