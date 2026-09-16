<script setup lang="ts">
/**
 * PersonaDocumentManager - thin orchestration layer for the documents slice.
 *
 * Composes the presentational atoms (DocumentList, DocumentForm) with the
 * useDocuments composable and stays out of the way: all state is either
 * passed in via props or held by the composable. On any successful
 * mutation it emits `updated` so the host page can refetch props.
 */
import { useDocuments } from '../../composables/useDocuments';
import DocumentList from './DocumentList.vue';
import DocumentForm from './DocumentForm.vue';
import type { CreateDocumentInput, Document, PersonaId } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  documents: Document[];
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

const emit = defineEmits<{
  updated: [];
}>();

const {
  isLoading,
  errors,
  addDocument,
  attachFile,
  deleteDocument,
} = useDocuments(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleAddDocument(payload: CreateDocumentInput): void {
  addDocument(payload);
}

function handleAttachFile(documentId: PersonaId, file: File, side: string): void {
  attachFile(documentId, file, side);
}

function handleDeleteDocument(documentId: PersonaId): void {
  deleteDocument(documentId);
}
</script>

<template>
  <section class="persona-documents-manager" data-testid="persona-documents-manager">
    <DocumentList
      :documents="documents"
      :busy="isLoading"
      @attach="handleAttachFile"
      @delete="handleDeleteDocument"
    />

    <p
      v-for="(message, field) in errors"
      :key="field"
      class="persona-documents-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <DocumentForm
      :busy="isLoading"
      @submit="handleAddDocument"
    />
  </section>
</template>