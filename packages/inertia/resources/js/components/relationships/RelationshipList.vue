<script setup lang="ts">
/**
 * RelationshipList - iterates an array of relationships and renders a
 * RelationshipItem per row, forwarding the `delete` intent up to its parent.
 * Pure: takes relationships as a prop and never mutates state itself.
 */
import RelationshipItem from './RelationshipItem.vue';
import type { PersonaId, Relationship } from '../../types/persona';

defineProps<{
  relationships: Relationship[];
  /** Disables every item's action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  delete: [relationshipId: PersonaId];
}>();

function forwardDelete(relationshipId: PersonaId): void {
  emit('delete', relationshipId);
}
</script>

<template>
  <ul class="persona-relationship-list">
    <li
      v-for="relationship in relationships"
      :key="relationship.id"
      class="persona-relationship-list__item"
    >
      <RelationshipItem
        :relationship="relationship"
        :busy="busy"
        @delete="forwardDelete"
      />
    </li>
    <li v-if="relationships.length === 0" class="persona-relationship-list__empty">
      No relationships yet.
    </li>
  </ul>
</template>