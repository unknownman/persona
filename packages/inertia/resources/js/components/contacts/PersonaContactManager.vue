<script setup lang="ts">
/**
 * PersonaContactManager - thin orchestration layer for the contacts slice.
 *
 * Composes the presentational atoms (ContactList, ContactForm) with the
 * useContacts composable and stays out of the way: all state is either
 * passed in via props or held by the composable. On any successful
 * mutation it emits `updated` so the host page can refetch props.
 */
import { useContacts } from '../../composables/useContacts';
import ContactList from './ContactList.vue';
import ContactForm from './ContactForm.vue';
import type { Contact, CreateContactInput, PersonaId } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  contacts: Contact[];
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

interface PersonaContactManagerEmits {
  updated: [];
}

const emit = defineEmits<PersonaContactManagerEmits>();

const {
  isLoading,
  errors,
  addContact,
  deleteContact,
  makePrimary,
} = useContacts(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleAddContact(payload: CreateContactInput): void {
  addContact(payload);
}

function handleDeleteContact(contactId: PersonaId): void {
  deleteContact(contactId);
}

function handleSetPrimary(contactId: PersonaId): void {
  makePrimary(contactId);
}
</script>

<template>
  <section class="persona-contacts-manager" data-testid="persona-contacts-manager">
    <ContactList
      :contacts="contacts"
      :busy="isLoading"
      @set-primary="handleSetPrimary"
      @delete="handleDeleteContact"
    />

    <p
      v-for="(message, field) in errors"
      :key="field"
      class="persona-contacts-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <ContactForm
      :busy="isLoading"
      @submit="handleAddContact"
    />
  </section>
</template>