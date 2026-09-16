<script setup lang="ts">
/**
 * AddressItem - a single, atomic address row.
 * Pure: it never touches the network; it forwards intents to the parent
 * via `primary` / `delete` events.
 */
import { computed } from 'vue';
import type { Address, PersonaId } from '../../types/persona';

const { address, busy } = defineProps<{
  address: Address;
  /** Disables the action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  primary: [addressId: PersonaId];
  delete: [addressId: PersonaId];
}>();

/** Full locality string ("City, State 12345 US") with nulls collapsed. */
const locality = computed(() =>
  [address.city, address.state, address.zip_code, address.country_code]
    .filter((part): part is string => Boolean(part))
    .join(', '),
);

function emitPrimary(): void {
  emit('primary', address.id);
}

function emitDelete(): void {
  emit('delete', address.id);
}
</script>

<template>
  <article class="persona-address" :data-testid="`persona-address-${address.id}`">
    <div class="persona-address__main">
      <span class="persona-address__type">{{ address.type }}</span>
      <span class="persona-address__line1">{{ address.line_1 }}</span>
      <span v-if="address.line_2" class="persona-address__line2">
        {{ address.line_2 }}
      </span>
      <span v-if="locality" class="persona-address__locality">
        {{ locality }}
      </span>
      <span v-if="address.is_primary" class="persona-badge" data-variant="primary">
        primary
      </span>
    </div>

    <div class="persona-address__actions">
      <button
        v-if="!address.is_primary"
        type="button"
        class="persona-address__button"
        :disabled="busy"
        @click="emitPrimary"
      >
        Make primary
      </button>
      <button
        type="button"
        class="persona-address__button persona-address__button--danger"
        :disabled="busy"
        @click="emitDelete"
      >
        Remove
      </button>
    </div>
  </article>
</template>