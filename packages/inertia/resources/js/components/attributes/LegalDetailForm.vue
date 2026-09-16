<script setup lang="ts">
/**
 * LegalDetailForm - uncontrolled form for the legal-details slice
 * (a single upsert record per personable).
 *
 * Like PhysicalAttributeForm, this seeds its initial values from an existing
 * record (passed via `value`) and emits a typed payload on submit; the
 * parent/composable owns persistence.
 */
import { reactive, watch } from 'vue';
import type { CreateLegalDetailInput, LegalDetail } from '../../types/persona';

const props = defineProps<{
  /** Current record, when one exists. Used to seed initial field values. */
  value?: LegalDetail | null;
  /** Disables the form while a mutation is in flight upstream. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  submit: [payload: CreateLegalDetailInput];
}>();

const form = reactive<{
  nationality: string;
  maritalStatus: string;
  taxId: string;
}>({
  nationality: '',
  maritalStatus: '',
  taxId: '',
});

function seed(existing: LegalDetail | null | undefined): void {
  form.nationality = existing?.nationality ?? '';
  form.maritalStatus = existing?.marital_status ?? '';
  form.taxId = existing?.tax_id ?? '';
}

seed(props.value);

watch(() => props.value, (next) => seed(next), { deep: true });

function handleSubmit(): void {
  emit('submit', {
    nationality: form.nationality.trim() || null,
    marital_status: form.maritalStatus.trim() || null,
    tax_id: form.taxId.trim() || null,
  });
}
</script>

<template>
  <form
    class="persona-legal-form"
    aria-label="Update legal details"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-legal-form__field">
      <label class="persona-legal-form__label" for="persona-legal-nationality">
        Nationality
      </label>
      <input
        id="persona-legal-nationality"
        v-model="form.nationality"
        class="persona-legal-form__input"
        type="text"
        placeholder="US"
        :disabled="busy"
      >
    </div>

    <div class="persona-legal-form__row">
      <div class="persona-legal-form__field">
        <label class="persona-legal-form__label" for="persona-legal-marital">
          Marital status
        </label>
        <input
          id="persona-legal-marital"
          v-model="form.maritalStatus"
          class="persona-legal-form__input"
          type="text"
          placeholder="single"
          :disabled="busy"
        >
      </div>

      <div class="persona-legal-form__field">
        <label class="persona-legal-form__label" for="persona-legal-tax">
          Tax ID
        </label>
        <input
          id="persona-legal-tax"
          v-model="form.taxId"
          class="persona-legal-form__input"
          type="text"
          :disabled="busy"
        >
      </div>
    </div>

    <button
      type="submit"
      class="persona-legal-form__submit"
      :disabled="busy"
    >
      {{ busy ? 'Saving\u2026' : 'Save details' }}
    </button>
  </form>
</template>