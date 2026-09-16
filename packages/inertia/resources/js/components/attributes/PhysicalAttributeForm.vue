<script setup lang="ts">
/**
 * PhysicalAttributeForm - uncontrolled form for the physical-attributes
 * slice (a single upsert record per personable).
 *
 * Unlike list-backed forms, this one seeds its initial values from an
 * existing record (passed via `value`) so hosts can load the current
 * snapshot from props and edit in place. It emits a typed payload on
 * submit; the parent/composable owns persistence.
 */
import { reactive, watch } from 'vue';
import type { CreatePhysicalAttributeInput, PhysicalAttribute } from '../../types/persona';

const props = defineProps<{
  /** Current record, when one exists. Used to seed initial field values. */
  value?: PhysicalAttribute | null;
  /** Disables the form while a mutation is in flight upstream. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  submit: [payload: CreatePhysicalAttributeInput];
}>();

const form = reactive<{
  height: string;
  weight: string;
  eyeColor: string;
  hairColor: string;
  bloodType: string;
}>({
  height: '',
  weight: '',
  eyeColor: '',
  hairColor: '',
  bloodType: '',
});

function seed(existing: PhysicalAttribute | null | undefined): void {
  form.height = existing?.height?.toString() ?? '';
  form.weight = existing?.weight?.toString() ?? '';
  form.eyeColor = existing?.eye_color ?? '';
  form.hairColor = existing?.hair_color ?? '';
  form.bloodType = existing?.blood_type ?? '';
}

seed(props.value);

watch(() => props.value, (next) => seed(next), { deep: true });

function parseFloatOrNull(raw: string): number | null {
  const parsed = Number.parseFloat(raw);
  return Number.isFinite(parsed) ? parsed : null;
}

function handleSubmit(): void {
  emit('submit', {
    height: form.height === '' ? null : parseFloatOrNull(form.height),
    weight: form.weight === '' ? null : parseFloatOrNull(form.weight),
    eye_color: form.eyeColor.trim() || null,
    hair_color: form.hairColor.trim() || null,
    blood_type: form.bloodType.trim() || null,
  });
}
</script>

<template>
  <form
    class="persona-physical-form"
    aria-label="Update physical attributes"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-physical-form__row">
      <div class="persona-physical-form__field">
        <label class="persona-physical-form__label" for="persona-phys-height">
          Height (cm)
        </label>
        <input
          id="persona-phys-height"
          v-model="form.height"
          class="persona-physical-form__input"
          type="number"
          step="0.1"
          min="0"
          :disabled="busy"
        >
      </div>

      <div class="persona-physical-form__field">
        <label class="persona-physical-form__label" for="persona-phys-weight">
          Weight (kg)
        </label>
        <input
          id="persona-phys-weight"
          v-model="form.weight"
          class="persona-physical-form__input"
          type="number"
          step="0.1"
          min="0"
          :disabled="busy"
        >
      </div>
    </div>

    <div class="persona-physical-form__row">
      <div class="persona-physical-form__field">
        <label class="persona-physical-form__label" for="persona-phys-eye">
          Eye color
        </label>
        <input
          id="persona-phys-eye"
          v-model="form.eyeColor"
          class="persona-physical-form__input"
          type="text"
          :disabled="busy"
        >
      </div>

      <div class="persona-physical-form__field">
        <label class="persona-physical-form__label" for="persona-phys-hair">
          Hair color
        </label>
        <input
          id="persona-phys-hair"
          v-model="form.hairColor"
          class="persona-physical-form__input"
          type="text"
          :disabled="busy"
        >
      </div>

      <div class="persona-physical-form__field">
        <label class="persona-physical-form__label" for="persona-phys-blood">
          Blood type
        </label>
        <input
          id="persona-phys-blood"
          v-model="form.bloodType"
          class="persona-physical-form__input"
          type="text"
          placeholder="O+"
          :disabled="busy"
        >
      </div>
    </div>

    <button
      type="submit"
      class="persona-physical-form__submit"
      :disabled="busy"
    >
      {{ busy ? 'Saving\u2026' : 'Save attributes' }}
    </button>
  </form>
</template>