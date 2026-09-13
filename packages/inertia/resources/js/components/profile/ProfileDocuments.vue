<script setup lang="ts">
/**
 * ProfileDocuments - secure read-only document list.
 * Numbers are masked server-side and re-masked here; only the last 4
 * digits are ever rendered, and raw hashes stay on the backend.
 */
import DocumentStatusBadge from './DocumentStatusBadge.vue';
import { useProfile } from '../../composables/useProfile';
import type { Document } from '../../types/persona';

const { documents } = defineProps<{
  documents: Document[];
}>();

const { maskedNumber, isExpired } = useProfile(null);

function humanizeType(type: Document['type']): string {
  return type.replaceAll('_', ' ');
}

function expiryLabel(document: Document): string {
  if (!document.expires_at) return '';
  return isExpired(document)
    ? `Expired ${new Date(document.expires_at).toLocaleDateString()}`
    : `Expires ${new Date(document.expires_at).toLocaleDateString()}`;
}
</script>

<template>
  <section class="persona-profile__section" aria-label="Documents">
    <h3 class="persona-profile__heading">Documents</h3>
    <div class="persona-profile__documents">
      <article
        v-for="document in documents"
        :key="document.id"
        class="persona-document-item"
      >
        <div class="persona-document-item__main">
          <span class="persona-document-item__type">
            {{ humanizeType(document.type) }}
          </span>
          <span class="persona-document-item__number">
            {{ document.number ? maskedNumber(document.number) : '—' }}
          </span>
          <span
            v-if="document.expires_at"
            class="persona-document-item__expiry"
            :class="{ 'persona-document-item__expiry--expired': isExpired(document) }"
          >
            {{ expiryLabel(document) }}
          </span>
        </div>
        <DocumentStatusBadge :status="document.status" />
      </article>
    </div>
  </section>
</template>