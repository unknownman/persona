<script setup lang="ts">
/**
 * ProfileAddresses - addresses section grouped by type (home, work, ...).
 */
import { computed } from 'vue';
import AddressCard from './AddressCard.vue';
import type { Address } from '../../types/persona';

const { addresses } = defineProps<{
  addresses: Address[];
}>();

const grouped = computed(() => {
  const groups = new Map<string, Address[]>();
  for (const address of addresses) {
    const list = groups.get(address.type) ?? [];
    list.push(address);
    groups.set(address.type, list);
  }
  return groups;
});
</script>

<template>
  <section class="persona-profile__section" aria-label="Addresses">
    <h3 class="persona-profile__heading">Addresses</h3>
    <div
      v-for="([type, group]) in grouped"
      :key="type"
      class="persona-profile__address-group"
    >
      <span class="persona-profile__group-label">
        {{ type.charAt(0).toUpperCase() + type.slice(1) }}
      </span>
      <AddressCard
        v-for="address in group"
        :key="address.id"
        :address="address"
      />
    </div>
  </section>
</template>