<script setup lang="ts">
/**
 * PersonaAddressManager - thin orchestration layer for the addresses slice.
 *
 * Composes the presentational atoms (AddressList, AddressForm) with the
 * useAddresses composable and stays out of the way: all state is either
 * passed in via props or held by the composable. On any successful
 * mutation it emits `updated` so the host page can refetch props.
 */
import { useAddresses } from '../../composables/useAddresses';
import AddressList from './AddressList.vue';
import AddressForm from './AddressForm.vue';
import type { Address, CreateAddressInput, PersonaId } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  addresses: Address[];
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

const emit = defineEmits<{
  updated: [];
}>();

const {
  isLoading,
  errors,
  addAddress,
  makePrimary,
  deleteAddress,
} = useAddresses(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleAddAddress(payload: CreateAddressInput): void {
  addAddress(payload);
}

function handleSetPrimary(addressId: PersonaId): void {
  makePrimary(addressId);
}

function handleDeleteAddress(addressId: PersonaId): void {
  deleteAddress(addressId);
}
</script>

<template>
  <section class="persona-addresses-manager" data-testid="persona-addresses-manager">
    <AddressList
      :addresses="addresses"
      :busy="isLoading"
      @set-primary="handleSetPrimary"
      @delete="handleDeleteAddress"
    />

    <p
      v-for="(message, field) in errors"
      :key="field"
      class="persona-addresses-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <AddressForm
      :busy="isLoading"
      @submit="handleAddAddress"
    />
  </section>
</template>