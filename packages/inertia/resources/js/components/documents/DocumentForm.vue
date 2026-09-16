<script setup lang="ts">
/**
 * DocumentForm - pure, uncontrolled creation form for a single document.
 * State lives entirely inside this component; it emits a typed payload
 * on submit and resets so the parent never has to manage form internals.
 */
import { ref } from 'vue';
import type { CreateDocumentInput, DocumentType } from '../../types/persona';

const props = withDefaults(
  defineProps<{
    /** Disables the form while a mutation is in flight upstream. */
    busy?: boolean;
    /** Override the list of types shown in the dropdown. */
    typeOptions?: readonly DocumentType[];
  }>(),
  {
    busy: false,
    typeOptions: () => [
      'passport',
      'national_id',
      'driving_license',
      'birth_certificate',
      'residence_permit',
      'visa',
      'tax_id',
      'social_security',
    ],
  },
);

const emit = defineEmits<{
  submit: [payload: CreateDocumentInput];
}>();

const type = ref<DocumentType>(props.typeOptions[0]);
const number = ref('');
const countryCode = ref('');
const issuedAt = ref('');
const expiresAt = ref('');

const isValid = (): boolean => number.value.trim().length > 0;

function handleSubmit(): void {
  if (!isValid()) return;

  emit('submit', {
    type: type.value,
    number: number.value.trim(),
    country_code: countryCode.value.trim() || null,
    issued_at: issuedAt.value || null,
    expires_at: expiresAt.value || null,
  });

  number.value = '';
  countryCode.value = '';
  issuedAt.value = '';
  expiresAt.value = '';
}
</script>

<template>
  <form
    class="persona-document-form"
    aria-label="Add a new document"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-document-form__row">
      <div class="persona-document-form__field">
        <label class="persona-document-form__label" for="persona-doc-type">
          Type
        </label>
        <select
          id="persona-doc-type"
          v-model="type"
          class="persona-document-form__input"
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

      <div class="persona-document-form__field">
        <label class="persona-document-form__label" for="persona-doc-number">
          Number
        </label>
        <input
          id="persona-doc-number"
          v-model="number"
          class="persona-document-form__input"
          type="text"
          placeholder="AB1234567"
          required
          :disabled="busy"
        >
      </div>
    </div>

    <div class="persona-document-form__row">
      <div class="persona-document-form__field">
        <label class="persona-document-form__label" for="persona-doc-country">
          Country (ISO-2)
        </label>
        <input
          id="persona-doc-country"
          v-model="countryCode"
          class="persona-document-form__input"
          type="text"
          maxlength="2"
          placeholder="US"
          :disabled="busy"
        >
      </div>

      <div class="persona-document-form__field">
        <label class="persona-document-form__label" for="persona-doc-issued">
          Issued at
        </label>
        <input
          id="persona-doc-issued"
          v-model="issuedAt"
          class="persona-document-form__input"
          type="date"
          :disabled="busy"
        >
      </div>

      <div class="persona-document-form__field">
        <label class="persona-document-form__label" for="persona-doc-expires">
          Expires at
        </label>
        <input
          id="persona-doc-expires"
          v-model="expiresAt"
          class="persona-document-form__input"
          type="date"
          :disabled="busy"
        >
      </div>
    </div>

    <button
      type="submit"
      class="persona-document-form__submit"
      :disabled="busy || !isValid()"
    >
      {{ busy ? 'Adding\u2026' : 'Add document' }}
    </button>
  </form>
</template>