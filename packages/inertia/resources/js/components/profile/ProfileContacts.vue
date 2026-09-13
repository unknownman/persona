<script setup lang="ts">
/**
 * ProfileContacts - read-only contact list section.
 * Each row is delegated to the atomic ContactCard.
 */
import ContactCard from './ContactCard.vue';
import type { Contact } from '../../types/persona';

defineProps<{
  contacts: Contact[];
}>();
</script>

<template>
  <section class="persona-profile__section" aria-label="Contacts">
    <h3 class="persona-profile__heading">Contacts</h3>
    <div class="persona-profile__contacts">
      <ContactCard
        v-for="contact in contacts"
        :key="contact.id"
        :contact="contact"
      />
    </div>
  </section>
</template>