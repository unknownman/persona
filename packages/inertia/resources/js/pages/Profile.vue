<script setup lang="ts">
/**
 * Profile - the minimal Inertia page component Persona ships with.
 *
 * The controller stub renders `Inertia::render('Profile', ...)`, so a host
 * that publishes the controller and this page gets a working profile at
 * `/persona` with zero hand-written glue. The page is intentionally thin:
 * all layout lives in `ProfileOverview`; hosts that need mutating UIs can
 * compose the domain *Managers alongside it or swap this page entirely.
 */
import ProfileOverview, { type PersonableBag } from '../components/profile/ProfileOverview.vue';
import type { PersonableScope, Relationship, SocialActivity } from '../types/persona';

defineProps<{
  personable: PersonableBag;
  relationships: Relationship[];
  socialActivities?: Record<string, SocialActivity[]>;
  personaScope: PersonableScope;
}>();
</script>

<template>
  <main class="persona-page">
    <ProfileOverview
      :personable="personable"
      :relationships="relationships"
      :social-activities="socialActivities"
      :persona-scope="personaScope"
    />
  </main>
</template>