<script setup lang="ts">
/**
 * SocialAccountForm - pure, uncontrolled creation form for a social account.
 * State lives entirely inside this component; it emits a typed payload
 * on submit and resets so the parent never has to manage form internals.
 */
import { ref } from 'vue';
import type { CreateSocialAccountInput, SocialPlatform } from '../../types/persona';

const props = defineProps<{
    /** Disables the form while a mutation is in flight upstream. */
    busy?: boolean;
    /** Override the list of platforms shown in the dropdown. */
    platformOptions: readonly SocialPlatform[];
  }>();

const emit = defineEmits<{
  submit: [payload: CreateSocialAccountInput];
}>();

const platform = ref<SocialPlatform>(props.platformOptions[0] || '');
const username = ref('');
const url = ref('');
const isPrimary = ref(false);

const isValid = (): boolean => username.value.trim().length > 0;

function handleSubmit(): void {
  if (!isValid()) return;

  emit('submit', {
    platform: platform.value,
    username: username.value.trim(),
    url: url.value.trim() || null,
    is_primary: isPrimary.value,
  });

  username.value = '';
  url.value = '';
  isPrimary.value = false;
}
</script>

<template>
  <form
    class="persona-social-form"
    aria-label="Add a new social account"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-social-form__row">
      <div class="persona-social-form__field">
        <label class="persona-social-form__label" for="persona-social-platform">
          Platform
        </label>
        <select
          id="persona-social-platform"
          v-model="platform"
          class="persona-social-form__input"
          :disabled="busy"
        >
          <option
            v-for="option in platformOptions"
            :key="option"
            :value="option"
          >
            {{ option }}
          </option>
        </select>
      </div>

      <div class="persona-social-form__field">
        <label class="persona-social-form__label" for="persona-social-username">
          Username
        </label>
        <input
          id="persona-social-username"
          v-model="username"
          class="persona-social-form__input"
          type="text"
          placeholder="@handle"
          required
          :disabled="busy"
        >
      </div>
    </div>

    <div class="persona-social-form__field">
      <label class="persona-social-form__label" for="persona-social-url">
        Profile URL
      </label>
      <input
        id="persona-social-url"
        v-model="url"
        class="persona-social-form__input"
        type="url"
        placeholder="https://..."
        :disabled="busy"
      >
    </div>

    <label class="persona-social-form__check">
      <input
        v-model="isPrimary"
        type="checkbox"
        :disabled="busy"
      >
      Make primary
    </label>

    <button
      type="submit"
      class="persona-social-form__submit"
      :disabled="busy || !isValid()"
    >
      {{ busy ? 'Adding\u2026' : 'Add account' }}
    </button>
  </form>
</template>