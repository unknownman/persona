<script setup lang="ts">
import { onMounted } from 'vue';
import { usePersona } from '../composables/usePersona';

const props = defineProps<{
  personableType: string;
  personableId: number | string;
}>();

const {
  loading,
  error,
  success,
  contacts,
  contactForm,
  fetchProfile,
  fetchContacts,
  addContact,
  removeContact,
  setPrimaryContact,
  clearSuccess,
} = usePersona({
  personableType: props.personableType,
  personableId: props.personableId,
});

onMounted(() => {
  void fetchContacts();
  void fetchProfile();
});

const hasValue = (): boolean => contactForm.data.value.trim().length > 0;
const busy = (): boolean => loading.value || contactForm.processing;
</script>

<template>
  <section class="persona-contacts" data-testid="persona-contacts">
    <h2 class="persona-contacts__title">Contacts</h2>

    <p
      v-for="(fieldError, name) in (contactForm.errors as Record<string, string>)"
      :key="name"
      class="persona-contacts__error"
      role="alert"
    >
      {{ fieldError }}
    </p>
    <p v-if="error" class="persona-contacts__error" role="alert">{{ error }}</p>
    <p v-if="success" class="persona-contacts__success" role="status" @click="clearSuccess">{{ success }}</p>

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
            :disabled="busy()"
            @click="setPrimaryContact(contact.id)"
          >
            Make primary
          </button>
          <button
            type="button"
            class="persona-contacts__button persona-contacts__button--danger"
            :disabled="busy()"
            @click="removeContact(contact.id)"
          >
            Remove
          </button>
        </span>
      </li>
    </ul>
    <p v-else class="persona-contacts__empty">No contacts yet.</p>

    <form class="persona-contacts__form" :aria-busy="busy()" @submit.prevent="addContact">
      <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-type">Type</label>
        <select
          id="persona-type"
          v-model="contactForm.data.type"
          class="persona-contacts__input"
          :disabled="contactForm.processing"
          @change="contactForm.setData('type', contactForm.data.type)"
        >
          <option value="email">Email</option>
          <option value="phone">Phone</option>
          <option value="handle">Handle</option>
        </select>
      </div>

      <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-value">Value</label>
        <input
          id="persona-value"
          v-model="contactForm.data.value"
          class="persona-contacts__input"
          type="text"
          placeholder="user@example.com, +1234567890, or @handle"
          required
          :disabled="contactForm.processing"
        >
      </div>

      <label class="persona-contacts__check">
        <input
          type="checkbox"
          :checked="contactForm.data.isPrimary"
          @change="contactForm.setData('isPrimary', $event.target.checked)"
          :disabled="contactForm.processing"
        >
        Make primary
      </label>

      <label class="persona-contacts__check">
        <input
          type="checkbox"
          :checked="contactForm.data.isEmergency"
          @change="contactForm.setData('isEmergency', $event.target.checked)"
          :disabled="contactForm.processing"
        >
        Emergency contact
      </label>

      <button type="submit" class="persona-contacts__submit" :disabled="contactForm.processing || !hasValue()">
        {{ contactForm.processing ? 'Adding…' : 'Add contact' }}
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