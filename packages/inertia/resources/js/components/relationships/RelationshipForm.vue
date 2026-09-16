<script setup lang="ts">
/**
 * RelationshipForm - pure, uncontrolled creation form for a relationship.
 * State lives entirely inside this component; it emits a typed payload
 * (related morph + type) on submit and resets so the parent never has to
 * manage form internals.
 */
import { ref } from 'vue';
import type { CreateRelationshipInput } from '../../composables/useRelationships';
import type { RelationshipType } from '../../types/persona';

const props = defineProps<{
    /** Disables the form while a mutation is in flight upstream. */
    busy?: boolean;
    /** Override the list of relationship types shown in the dropdown. */
    typeOptions: readonly RelationshipType[];
  }>();

const emit = defineEmits<{
  submit: [payload: CreateRelationshipInput];
}>();

const type = ref<RelationshipType>(props.typeOptions[0] || '');
const relatedType = ref('');
const relatedId = ref<string>('');

const isValid = (): boolean =>
  relatedType.value.trim().length > 0 && relatedId.value.trim().length > 0;

function handleSubmit(): void {
  if (!isValid()) return;

  emit('submit', {
    related_type: relatedType.value.trim(),
    related_id: relatedId.value.trim(),
    type: type.value,
  });

  relatedType.value = '';
  relatedId.value = '';
}
</script>

<template>
  <form
    class="persona-relationship-form"
    aria-label="Add a new relationship"
    :aria-busy="busy"
    @submit.prevent="handleSubmit"
  >
    <div class="persona-relationship-form__row">
      <div class="persona-relationship-form__field">
        <label class="persona-relationship-form__label" for="persona-rel-type">
          Relationship
        </label>
        <select
          id="persona-rel-type"
          v-model="type"
          class="persona-relationship-form__input"
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

      <div class="persona-relationship-form__field">
        <label class="persona-relationship-form__label" for="persona-rel-type">
          Related model
        </label>
        <input
          id="persona-rel-related-type"
          v-model="relatedType"
          class="persona-relationship-form__input"
          type="text"
          placeholder="App\Models\User"
          required
          :disabled="busy"
        >
      </div>

      <div class="persona-relationship-form__field">
        <label class="persona-relationship-form__label" for="persona-rel-related-id">
          Related ID
        </label>
        <input
          id="persona-rel-related-id"
          v-model="relatedId"
          class="persona-relationship-form__input"
          type="text"
          inputmode="numeric"
          placeholder="42"
          required
          :disabled="busy"
        >
      </div>
    </div>

    <button
      type="submit"
      class="persona-relationship-form__submit"
      :disabled="busy || !isValid()"
    >
      {{ busy ? 'Adding\u2026' : 'Add relationship' }}
    </button>
  </form>
</template>