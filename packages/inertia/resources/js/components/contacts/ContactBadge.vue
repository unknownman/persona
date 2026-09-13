<script setup lang="ts">
/**
 * ContactBadge - a tiny, single-purpose status pill.
 * Unstyled by design: hosts style the `.persona-badge` class (or the
 * `data-variant` attribute) with Tailwind, Bootstrap, or custom CSS.
 */
withDefaults(
  defineProps<{
    type?: 'primary' | 'emergency' | 'verified';
    label?: string;
  }>(),
  {
    type: 'primary',
    label: undefined,
  },
);
</script>

<template>
  <span class="persona-badge" :data-variant="type" role="status">
    {{ label ?? type }}
  </span>
</template>