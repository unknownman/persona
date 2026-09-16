<script setup lang="ts">
/**
 * DocumentItem - a single, atomic document row.
 * Pure: it never touches the network; it forwards intents to the parent
 * via `attach` / `delete` events.
 */
import { computed, ref } from 'vue';
import type { Document, PersonaId } from '../../types/persona';

const props = defineProps<{
  document: Document;
  /** Disables the action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  attach: [documentId: PersonaId, file: File, side: string];
  delete: [documentId: PersonaId];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const side = ref<'front' | 'back'>('front');

const isExpired = computed(() => {
  if (!props.document.expires_at) return false;
  return new Date(props.document.expires_at) < new Date();
});

const maskedNumber = computed(() =>
  props.document.number.length > 4
    ? `•••• ${props.document.number.slice(-4)}`
    : props.document.number,
);

function handleFileChange(event: Event): void {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];
  if (file) {
    emit('attach', props.document.id, file, side.value);
    input.value = '';
  }
}

function emitDelete(): void {
  emit('delete', props.document.id);
}
</script>

<template>
  <article class="persona-document" :data-testid="`persona-document-${document.id}`">
    <div class="persona-document__main">
      <span class="persona-document__type">{{ document.type }}</span>
      <span class="persona-document__number">{{ maskedNumber }}</span>
      <span class="persona-badge" :data-variant="document.status">
        {{ document.status }}
      </span>
      <span
        v-if="isExpired"
        class="persona-badge persona-badge--expired"
        data-variant="expired"
      >
        expired
      </span>
    </div>

    <div class="persona-document__actions">
      <select
        v-model="side"
        class="persona-document__select"
        :disabled="busy"
      >
        <option value="front">Front</option>
        <option value="back">Back</option>
      </select>

      <label class="persona-document__upload" :class="{ 'persona-document__upload--busy': busy }">
        <input
          ref="fileInput"
          type="file"
          class="persona-document__file-input"
          :disabled="busy"
          @change="handleFileChange"
        >
        Attach file
      </label>

      <button
        type="button"
        class="persona-document__button persona-document__button--danger"
        :disabled="busy"
        @click="emitDelete"
      >
        Remove
      </button>
    </div>
  </article>
</template>