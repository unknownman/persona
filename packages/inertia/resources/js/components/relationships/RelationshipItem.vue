<script setup lang="ts">
/**
 * RelationshipItem - a single, atomic relationship row.
 * Pure: it never touches the network; it forwards the `delete` intent to the
 * parent.
 */
import type { PersonaId, Relationship } from '../../types/persona';

const { relationship, busy } = defineProps<{
  relationship: Relationship;
  /** Disables the action button while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  delete: [relationshipId: PersonaId];
}>();

function emitDelete(): void {
  emit('delete', relationship.id);
}
</script>

<template>
  <article class="persona-relationship" :data-testid="`persona-relationship-${relationship.id}`">
    <div class="persona-relationship__main">
      <span class="persona-relationship__type">{{ relationship.type }}</span>
      <span class="persona-relationship__related">
        {{ relationship.related_personable_type }} #{{ relationship.related_personable_id }}
      </span>
    </div>

    <div class="persona-relationship__actions">
      <button
        type="button"
        class="persona-relationship__button persona-relationship__button--danger"
        :disabled="busy"
        @click="emitDelete"
      >
        Remove
      </button>
    </div>
  </article>
</template>