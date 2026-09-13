<script setup lang="ts">
/**
 * ProfileAttributes - read-only key/value grid for physical and legal
 * specifications. Each section collapses independently when absent.
 */
import type { LegalDetail, PhysicalAttribute } from '../../types/persona';

defineProps<{
  physical: PhysicalAttribute | null;
  legal: LegalDetail | null;
}>();

function metric(value: number | null, unit: string): string {
  return value != null ? `${value} ${unit}` : '';
}
</script>

<template>
  <div class="persona-profile__attributes">
    <section
      v-if="physical && (physical.height != null || physical.weight != null || physical.eye_color || physical.hair_color || physical.blood_type)"
      class="persona-profile__section"
      aria-label="Physical attributes"
    >
      <h3 class="persona-profile__heading">Physical attributes</h3>
      <dl class="persona-attributes-table">
        <div v-if="physical.height != null" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Height</dt>
          <dd class="persona-attributes-table__value">{{ metric(physical.height, 'cm') }}</dd>
        </div>
        <div v-if="physical.weight != null" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Weight</dt>
          <dd class="persona-attributes-table__value">{{ metric(physical.weight, 'kg') }}</dd>
        </div>
        <div v-if="physical.eye_color" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Eye color</dt>
          <dd class="persona-attributes-table__value">{{ physical.eye_color }}</dd>
        </div>
        <div v-if="physical.hair_color" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Hair color</dt>
          <dd class="persona-attributes-table__value">{{ physical.hair_color }}</dd>
        </div>
        <div v-if="physical.blood_type" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Blood type</dt>
          <dd class="persona-attributes-table__value">{{ physical.blood_type }}</dd>
        </div>
      </dl>
    </section>

    <section
      v-if="legal && (legal.nationality || legal.marital_status || legal.tax_id)"
      class="persona-profile__section"
      aria-label="Legal details"
    >
      <h3 class="persona-profile__heading">Legal details</h3>
      <dl class="persona-attributes-table">
        <div v-if="legal.nationality" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Nationality</dt>
          <dd class="persona-attributes-table__value">{{ legal.nationality }}</dd>
        </div>
        <div v-if="legal.marital_status" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Marital status</dt>
          <dd class="persona-attributes-table__value">{{ legal.marital_status }}</dd>
        </div>
        <div v-if="legal.tax_id" class="persona-attributes-table__row">
          <dt class="persona-attributes-table__key">Tax ID</dt>
          <dd class="persona-attributes-table__value">{{ legal.tax_id }}</dd>
        </div>
      </dl>
    </section>
  </div>
</template>