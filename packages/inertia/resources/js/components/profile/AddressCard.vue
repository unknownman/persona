<script setup lang="ts">
/**
 * AddressCard - a single formatted physical address with a primary tag.
 */
import type { Address } from '../../types/persona';

defineProps<{
  address: Address;
}>();

function locality(address: Address): string {
  return [address.city, address.state, address.country_code, address.zip_code]
    .filter(Boolean)
    .join(', ');
}
</script>

<template>
  <article class="persona-address-card">
    <div class="persona-address-card__main">
      <span class="persona-address-card__line1">{{ address.line_1 }}</span>
      <span v-if="address.line_2" class="persona-address-card__line2">{{ address.line_2 }}</span>
      <span v-if="locality(address)" class="persona-address-card__locality">{{ locality(address) }}</span>
    </div>

    <span
      v-if="address.is_primary"
      class="persona-address-badge persona-address-badge--primary"
      aria-label="Primary address"
    >
      Primary
    </span>
  </article>
</template>