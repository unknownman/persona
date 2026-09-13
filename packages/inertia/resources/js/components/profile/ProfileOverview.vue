<script setup lang="ts">
/**
 * ProfileOverview - the top-level orchestrator for an entity's profile.
 *
 * Thin by design: it receives a fully-serialized `personable` bag plus the
 * two read-only enrichments (relationships + resolved social activity) and
 * delegates each section to a dedicated atomic component.
 */
import ProfileHeader from './ProfileHeader.vue';
import ProfileContacts from './ProfileContacts.vue';
import ProfileAddresses from './ProfileAddresses.vue';
import ProfileDocuments from './ProfileDocuments.vue';
import ProfileSocials from './ProfileSocials.vue';
import ProfileRelationships from './ProfileRelationships.vue';
import ProfileAttributes from './ProfileAttributes.vue';
import type {
  Address,
  Contact,
  Document,
  LegalDetail,
  PersonableScope,
  PhysicalAttribute,
  Profile,
  Relationship,
  SocialAccount,
  SocialActivity,
} from '../../types/persona';

/** Serialized personable with every Persona section eager-loaded. */
export interface PersonableBag {
  profile: Profile | null;
  contacts: Contact[];
  addresses: Address[];
  documents: Document[];
  social_accounts: SocialAccount[];
  physical_attribute: PhysicalAttribute | null;
  legal_detail: LegalDetail | null;
}

const { personable, relationships, socialActivities, personaScope } = defineProps<{
  personable: PersonableBag;
  relationships: Relationship[];
  /** Resolved activity per social account id (from the backend resolver). */
  socialActivities?: Record<string, SocialActivity[]>;
  personaScope: PersonableScope;
}>();
</script>

<template>
  <section class="persona-profile" aria-label="Profile overview">
    <ProfileHeader v-if="personable.profile" :profile="personable.profile" />
    <ProfileContacts
      v-if="personable.contacts.length"
      :contacts="personable.contacts"
    />
    <ProfileAddresses
      v-if="personable.addresses.length"
      :addresses="personable.addresses"
    />
    <ProfileDocuments
      v-if="personable.documents.length"
      :documents="personable.documents"
    />
    <ProfileSocials
      v-if="personable.social_accounts.length"
      :accounts="personable.social_accounts"
      :activities="socialActivities ?? {}"
    />
    <ProfileRelationships
      v-if="relationships.length"
      :relationships="relationships"
      :persona-scope="personaScope"
    />
    <ProfileAttributes
      :physical="personable.physical_attribute"
      :legal="personable.legal_detail"
    />
  </section>
</template>