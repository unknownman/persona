<script setup lang="ts">
/**
 * DocumentList - iterates an array of documents and renders a DocumentItem per
 * row, forwarding the `attach` / `delete` intents up to its parent.
 * Pure: takes documents as a prop and never mutates state itself.
 */
import DocumentItem from './DocumentItem.vue';
import type { Document, PersonaId } from '../../types/persona';

defineProps<{
  documents: Document[];
  /** Disables every item's action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  attach: [documentId: PersonaId, file: File, side: string];
  delete: [documentId: PersonaId];
}>();

function forwardAttach(documentId: PersonaId, file: File, side: string): void {
  emit('attach', documentId, file, side);
}

function forwardDelete(documentId: PersonaId): void {
  emit('delete', documentId);
}
</script>

<template>
  <ul class="persona-document-list">
    <li
      v-for="document in documents"
      :key="document.id"
      class="persona-document-list__item"
    >
      <DocumentItem
        :document="document"
        :busy="busy"
        @attach="forwardAttach"
        @delete="forwardDelete"
      />
    </li>
    <li v-if="documents.length === 0" class="persona-document-list__empty">
      No documents yet.
    </li>
  </ul>
</template>