<script setup lang="ts">
/**
 * ContactList - iterates an array of contacts and renders a ContactItem per
 * row, forwarding the `set-primary` / `delete` intents up to its parent.
 * Pure: takes contacts as a prop and never mutates state itself.
 */
import ContactItem from './ContactItem.vue';
import type { Contact, PersonaId } from '../../types/persona';

defineProps<{
  contacts: Contact[];
  /** Disables every item's action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  'set-primary': [contactId: PersonaId];
  delete: [contactId: PersonaId];
}>();

function forwardPrimary(contactId: PersonaId): void {
  emit('set-primary', contactId);
}

function forwardDelete(contactId: PersonaId): void {
  emit('delete', contactId);
}
</script>

<template>
  <ul class="persona-contact-list">
    <li
      v-for="contact in contacts"
      :key="contact.id"
      class="persona-contact-list__item"
    >
      <ContactItem
        :contact="contact"
        :busy="busy"
        @primary="forwardPrimary"
        @delete="forwardDelete"
      />
    </li>
    <li v-if="contacts.length === 0" class="persona-contact-list__empty">
      No contacts yet.
    </li>
  </ul>
</template>