/**
 * useProfile - pure derived helpers for the profile view layer.
 *
 * Stays deliberately thin: no network calls, no store. Every helper is a
 * pure function (or a `computed` when it depends on the given profile) so
 * the atomic profile components stay dumb and testable.
 */
import { computed, type ComputedRef } from 'vue';
import type { Contact, Document, Profile } from '../types/persona';

export interface UseProfileReturn {
  /** Avatar fallback initials derived from the profile name. */
  initials: ComputedRef<string>;
  /** Mask a document number so only the last 4 digits are visible. */
  maskedNumber: (number: string) => string;
  /** Whether a document has passed its expiry date. */
  isExpired: (document: Document) => boolean;
  /** Action href for a contact (`mailto:`, `tel:`), or null when not actionable. */
  contactHref: (contact: Contact) => string | null;
  /** Human label for a contact action (Email / Call). */
  contactActionLabel: (contact: Contact) => string;
}

export function useProfile(profile: Profile | null): UseProfileReturn {
  const initials = computed(() => {
    const first = profile?.first_name ?? '';
    const last = profile?.last_name ?? '';
    return ((first[0] ?? '') + (last[0] ?? '')).toUpperCase();
  });

  function maskedNumber(number: string): string {
    return number.length > 4 ? `•••• ${number.slice(-4)}` : number;
  }

  function isExpired(document: Document): boolean {
    if (!document.expires_at) return false;
    return new Date(document.expires_at) < new Date();
  }

  function contactHref(contact: Contact): string | null {
    if (contact.type === 'email') return `mailto:${contact.value}`;
    if (contact.type === 'phone') return `tel:${contact.value}`;
    return null;
  }

  function contactActionLabel(contact: Contact): string {
    if (contact.type === 'email') return 'Email';
    if (contact.type === 'phone') return 'Call';
    return contact.type;
  }

  return { initials, maskedNumber, isExpired, contactHref, contactActionLabel };
}