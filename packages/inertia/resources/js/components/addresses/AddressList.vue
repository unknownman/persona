<script setup lang="ts">
/**
 * AddressList - iterates an array of addresses and renders an AddressItem per
 * row, forwarding the `set-primary` / `delete` intents up to its parent.
 * Pure: takes addresses as a prop and never mutates state itself.
 */
import AddressItem from './AddressItem.vue';
import type { Address, PersonaId } from '../../types/persona';

const { addresses, busy } = defineProps<{
  addresses: Address[];
  /** Disables every item's action buttons while any mutation is in flight. */
  busy?: boolean;
}>();

const emit = defineEmits<{
  'set-primary': [addressId: PersonaId];
  delete: [addressId: PersonaId];
}>();

function forwardPrimary(addressId: PersonaId): void {
  emit('set-primary', addressId);
}

function forwardDelete(addressId: PersonaId): void {
  emit('delete', addressId);
}
</script>

<template>
  <ul class="persona-address-list">
    <li
      v-for="address in addresses"
      :key="address.id"
      class="persona-address-list__item"
    >
      <AddressItem
        :address="address"
        :busy="busy"
        @primary="forwardPrimary"
        @delete="forwardDelete"
      />
    </li>
    <li v-if="addresses.length === 0" class="persona-address-list__empty">
      No addresses yet.
    </li>
  </ul>
</template>