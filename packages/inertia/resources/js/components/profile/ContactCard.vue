<script setup lang="ts">
/**
 * ContactCard - a single actionable contact card.
 *
 * Renders value + status pills plus protocol-aware links:
 * email -> `mailto:`, phone -> `tel:` and `sms:`. Non-actionable
 * types (e.g. handles) render without a protocol link.
 */
import type { Contact } from '../../types/persona';

const { contact } = defineProps<{
  contact: Contact;
}>();

function protocolHref(type: Contact['type'], value: string): string | undefined {
  if (type === 'email') return `mailto:${value}`;
  if (type === 'phone') return `tel:${value}`;
  return undefined;
}

function protocolLabel(type: Contact['type']): string {
  if (type === 'email') return 'Email';
  if (type === 'phone') return 'Call';
  return type;
}
</script>

<template>
  <article class="persona-contact-card">
    <div class="persona-contact-card__main">
      <span class="persona-contact-card__value">{{ contact.value }}</span>
      <span class="persona-contact-card__type">{{ contact.type }}</span>

      <div class="persona-contact-card__badges" aria-label="Status">
        <span
          v-if="contact.is_primary"
          class="persona-contact-badge persona-contact-badge--primary"
          aria-label="Primary contact"
        >
          Primary
        </span>
        <span
          v-if="contact.is_verified"
          class="persona-contact-badge persona-contact-badge--verified"
          aria-label="Verified"
        >
          Verified
        </span>
        <span
          v-if="contact.is_emergency"
          class="persona-contact-badge persona-contact-badge--emergency"
          aria-label="Emergency contact"
        >
          Emergency
        </span>
      </div>
    </div>

    <nav v-if="protocolHref(contact.type, contact.value)" class="persona-contact-card__actions" aria-label="Contact actions">
      <a
        :href="protocolHref(contact.type, contact.value)"
        class="persona-contact-card__link"
      >
        {{ protocolLabel(contact.type) }}
      </a>
      <a
        v-if="contact.type === 'phone'"
        :href="`sms:${contact.value}`"
        class="persona-contact-card__link"
      >
        SMS
      </a>
    </nav>
  </article>
</template>