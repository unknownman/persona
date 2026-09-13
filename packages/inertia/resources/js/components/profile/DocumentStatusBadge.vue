<script setup lang="ts">
/**
 * DocumentStatusBadge - lifecycle pill for a document's verification state.
 */
import type { DocumentStatus } from '../../types/persona';

const { status } = defineProps<{
  status: DocumentStatus;
}>();

const badgeClasses: Record<DocumentStatus, string> = {
  pending: 'persona-document-status--pending',
  verified: 'persona-document-status--verified',
  rejected: 'persona-document-status--rejected',
};

function badgeClass(value: DocumentStatus): string {
  return badgeClasses[value] ?? badgeClasses.pending;
}

function label(value: DocumentStatus): string {
  return value.charAt(0).toUpperCase() + value.slice(1);
}
</script>

<template>
  <span
    class="persona-document-status"
    :class="badgeClass(status)"
    :aria-label="`Status: ${status}`"
  >
    {{ label(status) }}
  </span>
</template>