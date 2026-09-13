<script setup lang="ts">
/**
 * ProfileRelationships - links to counterpart entities with a type pill.
 *
 * Each relationship is symmetric in storage (the model may appear on
 * either side), so the counterpart is the opposite side of the current
 * personable scope.
 */
import type { PersonableScope, Relationship } from '../../types/persona';

const { relationships, personaScope } = defineProps<{
  relationships: Relationship[];
  personaScope: PersonableScope;
}>();

function counterpart(rel: Relationship): string {
  const isSelf =
    rel.personable_type === personaScope.personable_type &&
    rel.personable_id === personaScope.personable_id;

  const type = isSelf ? rel.related_personable_type : rel.personable_type;
  const id = isSelf ? rel.related_personable_id : rel.personable_id;

  return `${type} #${id}`;
}

function pillLabel(type: Relationship['type']): string {
  return type.charAt(0).toUpperCase() + type.slice(1);
}
</script>

<template>
  <section class="persona-profile__section" aria-label="Relationships">
    <h3 class="persona-profile__heading">Relationships</h3>
    <div class="persona-profile__relationships">
      <div
        v-for="rel in relationships"
        :key="rel.id"
        class="persona-relationship-chip"
        :aria-label="`Relationship: ${rel.type}`"
      >
        <span class="persona-relationship-chip__type">{{ pillLabel(rel.type) }}</span>
        <span class="persona-relationship-chip__name">{{ counterpart(rel) }}</span>
      </div>
    </div>
  </section>
</template>