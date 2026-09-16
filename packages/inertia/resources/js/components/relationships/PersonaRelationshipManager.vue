<script setup lang="ts">
/**
 * PersonaRelationshipManager - thin orchestration layer for the
 * relationships slice.
 *
 * Composes the presentational atoms (RelationshipList, RelationshipForm)
 * with the useRelationships composable and stays out of the way: all state
 * is either passed in via props or held by the composable. On any successful
 * mutation it emits `updated` so the host page can refetch props.
 */
import { useRelationships } from '../../composables/useRelationships';
import RelationshipList from './RelationshipList.vue';
import RelationshipForm from './RelationshipForm.vue';
import type { CreateRelationshipInput } from '../../composables/useRelationships';
import type { PersonaId, Relationship } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  relationships: Relationship[];
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

const emit = defineEmits<{
  updated: [];
}>();

const {
  isLoading,
  errors,
  createRelationship,
  deleteRelationship,
} = useRelationships(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleCreateRelationship(payload: CreateRelationshipInput): void {
  createRelationship(payload);
}

function handleDeleteRelationship(relationshipId: PersonaId): void {
  deleteRelationship(relationshipId);
}
</script>

<template>
  <section class="persona-relationships-manager" data-testid="persona-relationships-manager">
    <RelationshipList
      :relationships="relationships"
      :busy="isLoading"
      @delete="handleDeleteRelationship"
    />

    <p
      v-for="(message, field) in errors"
      :key="field"
      class="persona-relationships-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <RelationshipForm
      :busy="isLoading"
      @submit="handleCreateRelationship"
    />
  </section>
</template>