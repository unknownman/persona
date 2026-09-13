/**
 * useContacts - a small, single-purpose composable for contact mutations.
 *
 * Every operation goes through the Inertia router so CSRF handling,
 * redirects, and `flash` messages behave exactly like a regular Inertia
 * form submission. Loading state is tracked per operation and endpoints
 * are derived from a configurable `basePath` (default `/persona`).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateContactInput, PersonaId } from '../types/persona';

/** The contact action currently in flight, if any. */
export type ContactAction = 'add' | 'delete' | 'primary' | 'verify';

/** Smoke-test-free surface for host applications to hook into. */
export interface UseContactsOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: ContactAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: ContactAction, errors: Record<string, string>) => void;
}

export interface UseContactsReturn {
  /** Whether any contact operation is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** The action currently in flight, or null when idle. */
  activeAction: Readonly<Ref<ContactAction | null>>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  /** Whether a specific action is currently in flight. */
  isBusy: (action: ContactAction) => boolean;
  addContact: (payload: CreateContactInput) => void;
  deleteContact: (contactId: PersonaId) => void;
  makePrimary: (contactId: PersonaId) => void;
  verifyOtp: (contactId: PersonaId, otp: string) => void;
}

/**
 * Endpoints are derived from a single configurable base path following the
 * standard Persona route conventions:
 *
 *   POST   /persona/contacts                -> addContact
 *   PATCH  /persona/contacts/{id}/primary   -> makePrimary
 *   POST   /persona/contacts/{id}/verify    -> verifyOtp
 *   DELETE /persona/contacts/{id}           -> deleteContact
 */
export function useContacts(
  personableType: string,
  personableId: PersonaId,
  options: UseContactsOptions = {},
): UseContactsReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const contactsUrl = `${base}/contacts`;

  const activeAction = ref<ContactAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function isBusy(action: ContactAction): boolean {
    return activeAction.value === action;
  }

  /** Build the Inertia visit options shared by every operation. */
  function visitOptions(action: ContactAction) {
    return {
      onStart: () => {
        activeAction.value = action;
        errors.value = {};
      },
      onFinish: () => {
        activeAction.value = null;
      },
      onSuccess: () => onSuccess?.(action),
      onError: (fieldErrors: Record<string, string>) => {
        errors.value = fieldErrors;
        onError?.(action, fieldErrors);
      },
    };
  }

  function addContact(payload: CreateContactInput): void {
    router.post(contactsUrl, { ...payload }, visitOptions('add'));
  }

  function deleteContact(contactId: PersonaId): void {
    router.delete(`${contactsUrl}/${contactId}`, visitOptions('delete'));
  }

  function makePrimary(contactId: PersonaId): void {
    router.patch(`${contactsUrl}/${contactId}/primary`, {}, visitOptions('primary'));
  }

  function verifyOtp(contactId: PersonaId, otp: string): void {
    router.post(`${contactsUrl}/${contactId}/verify`, { otp }, visitOptions('verify'));
  }

  return {
    isLoading,
    activeAction: readonly(activeAction),
    errors: readonly(errors),
    isBusy,
    addContact,
    deleteContact,
    makePrimary,
    verifyOtp,
  };
}