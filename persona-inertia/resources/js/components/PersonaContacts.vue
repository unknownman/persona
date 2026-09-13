<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { usePersona } from '../composables/usePersona';
import type { Contact } from '../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: number;
}>();

const { loading, error, fetchContacts, addContact, removeContact, setPrimaryContact } =
  usePersona();

const contacts = ref<Contact[]>([]);
const type = ref<'email' | 'phone' | 'handle'>('email');
const value = ref('');
const success = ref<string | null>(null);

async function load(): Promise<void> {
  contacts.value = await fetchContacts(props.personableType, props.personableId);
}

async function add(): Promise<void> {
  const created = await addContact(props.personableType, props.personableId, {
    type: type.value,
    value: value.value,
  });
  value.value = '';
  success.value = `${created.value} added as a ${created.type} contact.`;
  await load();
}

async function remove(contact: Contact): Promise<void> {
  await removeContact(contact.id);
  await load();
}

async function primary(contact: Contact): Promise<void> {
  await setPrimaryContact(contact.id);
  await load();
}

onMounted(load);
</script>

<template>
  <section class="persona-contacts" data-testid="persona-contacts">
    <h2 class="persona-contacts__title">Contacts</h2>

    <p v-if="error" class="persona-contacts__error" role="alert">{{ error }}</p>
    <p v-if="success" class="persona-contacts__success" role="status">{{ success }}</p>

    <ul v-if="contacts.length" class="persona-contacts__list">
      <li v-for="contact in contacts" :key="contact.id" class="persona-contacts__item">
        <span class="persona-contacts__value">{{ contact.value }}</span>
        <span class="persona-contacts__meta">
          {{ contact.type }}
          <span v-if="contact.isPrimary" class="persona-contacts__badge">primary</span>
          <span v-if="contact.isEmergency" class="persona-contacts__badge persona-contacts__badge--emergency">emergency</span>
        </span>
        <span class="persona-contacts__actions">
          <button
            v-if="!contact.isPrimary"
            type="button"
            class="persona-contacts__button"
            :disabled="loading"
            @click="primary(contact)"
          >
            Make primary
          </button>
          <button
            type="button"
            class="persona-contacts__button persona-contacts__button--danger"
            :disabled="loading"
            @click="remove(contact)"
          >
            Remove
          </button>
        </span>
      </li>
    </ul>
    <p v-else class="persona-contacts__empty">No contacts yet.</p>

    <form class="persona-contacts__form" :aria-busy="loading" @submit.prevent="add">
      <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-type">Type</label>
        <select id="persona-type" v-model="type" class="persona-contacts__input">
          <option value="email">Email</option>
          <option value="phone">Phone</option>
          <option value="handle">Handle</option>
        </select>
      </div>

      <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-value">Value</label>
        <input
          id="persona-value"
          v-model.trim="value"
          class="persona-contacts__input"
          type="text"
          placeholder="user@example.com, +1234567890, or @handle"
          required
        >
      </div>

      <button type="submit" class="persona-contacts__submit" :disabled="loading || !value">
        Add contact
      </button>
    </form>
  </section>
</template>

<style scoped>
.persona-contacts__list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.persona-contacts__item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0;
  border-bottom: 1px solid #e5e7eb;
}

.persona-contacts__value {
  font-weight: 600;
}

.persona-contacts__meta {
  font-size: 0.85em;
  color: #6b7280;
}

.persona-contacts__badge {
  margin-left: 0.25rem;
  padding: 0.125rem 0.375rem;
  border-radius: 999px;
  background: #dbeafe;
  color: #1d4ed8;
  font-size: 0.75em;
}

.persona-contacts__badge--emergency {
  background: #fee2e2;
  color: #b91c1c;
}

.persona-contacts__actions {
  margin-left: auto;
  display: flex;
  gap: 0.5rem;
}

.persona-contacts__form {
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  max-width: 24rem;
}

.persona-contacts__field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.persona-contacts__input {
  padding: 0.375rem 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}

.persona-contacts__error {
  color: #b91c1c;
}

.persona-contacts__success {
  color: #15803d;
}
</style>