<script setup lang="ts">
/**
 * ContactForm - pure, uncontrolled creation form for a single contact.
 * State lives entirely inside this component; it emits a typed payload
 * on submit and resets to its initial state so the parent never has to
 * manage form internals.
 */
import { ref } from 'vue';
import type { ContactType, CreateContactInput } from '../../types/persona';

const props = withDefaults(
  defineProps<{
    /** Disables the form while a mutation is in flight upstream. */
    busy?: boolean;
    /**
     * Override the list of types shown in the dropdown. Defaults to
     * email / phone / handle / username.
     */
    typeOptions?: readonly ContactType[];
  }>(),
  {
    busy: false,
    typeOptions: () => ['email', 'phone', 'handle', 'username'],
  },
);

const emit = defineEmits<{
  submit: [payload: CreateContactInput];
}>();

const type = ref<ContactType>(props.typeOptions[0]);
const value = ref('');
const isPrimary = ref(false);
const isEmergency = ref(false);

const isValid = (): boolean => value.value.trim().length > 0;

function handleSubmit(): void {
  if (!isValid()) return;

  emit('submit', {
    type: type.value,
    value: value.value.trim(),
    is_primary: isPrimary.value,
    is_emergency: isEmergency.value,
  });

  value.value = '';
  isPrimary.value = false;
  isEmergency.value = false;
}
</script>

<template>
  <form
    class="persona-contact-form"
    aria-label="Add a new contact"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-contact-form__field">
      <label class="persona-contact-form__label" for="persona-contact-type">
        Type
      </label>
      <select
        id="persona-contact-type"
        v-model="type"
        class="persona-contact-form__input"
        :disabled="busy"
      >
        <option
          v-for="option in typeOptions"
          :key="option"
          :value="option"
        >
          {{ option }}
        </option>
      </select>
    </div>

    <div class="persona-contact-form__field">
      <label class="persona-contact-form__label" for="persona-contact-value">
        Value
      </label>
      <input
        id="persona-contact-value"
        v-model="value"
        class="persona-contact-form__input"
        type="text"
        placeholder="user@example.com, +1234567890, or @handle"
        required
        :disabled="busy"
      >
    </div>

    <label class="persona-contact-form__check">
      <input
        v-model="isPrimary"
        type="checkbox"
        :disabled="busy"
      >
      Make primary
    </label>

    <label class="persona-contact-form__check">
      <input
        v-model="isEmergency"
        type="checkbox"
        :disabled="busy"
      >
      Emergency contact
    </label>

    <button
      type="submit"
      class="persona-contact-form__submit"
      :disabled="busy || !isValid()"
    >
      {{ busy ? 'Adding\u2026' : 'Add contact' }}
    </button>
  </form>
</template>