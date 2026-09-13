<script setup lang="ts">
/**
 * ContactItem - a single, atomic contact row.
 * Renders the value, its semantic status pills, and the mutation actions.
 * Pure: it never touches the network; it forwards intents to the parent
 * via `primary` / `delete` events.
 */
import ContactBadge from './ContactBadge.vue';
import type { Contact, PersonaId } from '../../types/persona';

const { contact, busy } = defineProps<{
  contact: Contact;
  /** Disables the action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  primary: [contactId: PersonaId];
  delete: [contactId: PersonaId];
}>();

function emitPrimary(): void {
  emit('primary', contact.id);
}

function emitDelete(): void {
  emit('delete', contact.id);
}
</script>

<template>
  <article class="persona-contact" :data-testid="`persona-contact-${contact.id}`">
    <div class="persona-contact__main">
      <span class="persona-contact__value">{{ contact.value }}</span>
      <span class="persona-contact__type">{{ contact.type }}</span>
      <ContactBadge v-if="contact.is_primary" type="primary" />
      <ContactBadge v-if="contact.is_verified" type="verified" />
      <ContactBadge v-if="contact.is_emergency" type="emergency" />
    </div>

    <div class="persona-contact__actions">
      <button
        v-if="!contact.is_primary"
        type="button"
        class="persona-contact__button"
        :disabled="busy"
        @click="emitPrimary"
      >
        Make primary
      </button>
      <button
        type="button"
        class="persona-contact__button persona-contact__button--danger"
        :disabled="busy"
        @click="emitDelete"
      >
        Remove
      </button>
    </div>
  </article>
</template>