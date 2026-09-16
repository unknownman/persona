<script setup lang="ts">
/**
 * AddressForm - pure, uncontrolled creation form for a single address.
 * State lives entirely inside this component; it emits a typed payload
 * on submit and resets so the parent never has to manage form internals.
 */
import { ref } from 'vue';
import type { AddressType, CreateAddressInput } from '../../types/persona';

const props = defineProps<{
    /** Disables the form while a mutation is in flight upstream. */
    busy?: boolean;
    /**
     * Override the list of types shown in the dropdown.
     */
    typeOptions: readonly AddressType[];
  }>();

const emit = defineEmits<{
  submit: [payload: CreateAddressInput];
}>();

const type = ref<AddressType>(props.typeOptions[0] || '');
const line1 = ref('');
const line2 = ref('');
const city = ref('');
const state = ref('');
const countryCode = ref('');
const zipCode = ref('');
const isPrimary = ref(false);

const isValid = (): boolean => line1.value.trim().length > 0;

function handleSubmit(): void {
  if (!isValid()) return;

  emit('submit', {
    type: type.value,
    line_1: line1.value.trim(),
    line_2: line2.value.trim() || null,
    city: city.value.trim() || null,
    state: state.value.trim() || null,
    country_code: countryCode.value.trim() || null,
    zip_code: zipCode.value.trim() || null,
    is_primary: isPrimary.value,
  });

  line1.value = '';
  line2.value = '';
  city.value = '';
  state.value = '';
  countryCode.value = '';
  zipCode.value = '';
  isPrimary.value = false;
}
</script>

<template>
  <form
    class="persona-address-form"
    aria-label="Add a new address"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-address-form__row">
      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-type">
          Type
        </label>
        <select
          id="persona-address-type"
          v-model="type"
          class="persona-address-form__input"
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

      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-line1">
          Address
        </label>
        <input
          id="persona-address-line1"
          v-model="line1"
          class="persona-address-form__input"
          type="text"
          placeholder="123 Main St"
          required
          :disabled="busy"
        >
      </div>
    </div>

    <div class="persona-address-form__row">
      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-line2">
          Line 2
        </label>
        <input
          id="persona-address-line2"
          v-model="line2"
          class="persona-address-form__input"
          type="text"
          :disabled="busy"
        >
      </div>

      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-city">
          City
        </label>
        <input
          id="persona-address-city"
          v-model="city"
          class="persona-address-form__input"
          type="text"
          :disabled="busy"
        >
      </div>
    </div>

    <div class="persona-address-form__row">
      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-state">
          State / Province
        </label>
        <input
          id="persona-address-state"
          v-model="state"
          class="persona-address-form__input"
          type="text"
          :disabled="busy"
        >
      </div>

      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-zip">
          Zip code
        </label>
        <input
          id="persona-address-zip"
          v-model="zipCode"
          class="persona-address-form__input"
          type="text"
          :disabled="busy"
        >
      </div>

      <div class="persona-address-form__field">
        <label class="persona-address-form__label" for="persona-address-country">
          Country (ISO-2)
        </label>
        <input
          id="persona-address-country"
          v-model="countryCode"
          class="persona-address-form__input"
          type="text"
          maxlength="2"
          placeholder="US"
          :disabled="busy"
        >
      </div>
    </div>

    <label class="persona-address-form__check">
      <input
        v-model="isPrimary"
        type="checkbox"
        :disabled="busy"
      >
      Make primary
    </label>

    <button
      type="submit"
      class="persona-address-form__submit"
      :disabled="busy || !isValid()"
    >
      {{ busy ? 'Adding\u2026' : 'Add address' }}
    </button>
  </form>
</template>