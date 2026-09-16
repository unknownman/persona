<script setup lang="ts">
/**
 * PersonaAttributesManager - thin orchestration layer for the two
 * single-record upsert slices: physical attributes and legal details.
 *
 * Both are one record per personable, so this component composes a pair of
 * forms with their respective composables and emits `updated` on any
 * successful mutation so the host page can refetch props.
 */
import { usePhysicalAttributes } from '../../composables/usePhysicalAttributes';
import { useLegalDetails } from '../../composables/useLegalDetails';
import PhysicalAttributeForm from './PhysicalAttributeForm.vue';
import LegalDetailForm from './LegalDetailForm.vue';
import type { CreateLegalDetailInput, CreatePhysicalAttributeInput, LegalDetail, PersonaId, PhysicalAttribute } from '../../types/persona';

const props = defineProps<{
  personableType: string;
  personableId: PersonaId;
  physicalAttributes?: PhysicalAttribute | null;
  legalDetails?: LegalDetail | null;
  /** Override the derived endpoint base path (defaults to "/persona"). */
  basePath?: string;
}>();

const emit = defineEmits<{
  updated: [];
}>();

const physical = usePhysicalAttributes(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

const legal = useLegalDetails(props.personableType, props.personableId, {
  basePath: props.basePath,
  onSuccess: () => emit('updated'),
});

function handleSavePhysical(payload: CreatePhysicalAttributeInput): void {
  physical.update(payload);
}

function handleSaveLegal(payload: CreateLegalDetailInput): void {
  legal.update(payload);
}
</script>

<template>
  <section class="persona-attributes-manager" data-testid="persona-attributes-manager">
    <h3 class="persona-attributes-manager__heading">Physical attributes</h3>
    <PhysicalAttributeForm
      :value="physicalAttributes"
      :busy="physical.isLoading"
      @submit="handleSavePhysical"
    />
    <p
      v-for="(message, field) in physical.errors"
      :key="`physical-${field}`"
      class="persona-attributes-manager__error"
      role="alert"
    >
      {{ message }}
    </p>

    <h3 class="persona-attributes-manager__heading">Legal details</h3>
    <LegalDetailForm
      :value="legalDetails"
      :busy="legal.isLoading"
      @submit="handleSaveLegal"
    />
    <p
      v-for="(message, field) in legal.errors"
      :key="`legal-${field}`"
      class="persona-attributes-manager__error"
      role="alert"
    >
      {{ message }}
    </p>
  </section>
</template>