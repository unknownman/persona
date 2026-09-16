/**
 * useAddresses - a small, single-purpose composable for address mutations.
 *
 * Every operation goes through the Inertia router so CSRF handling,
 * redirects, and `flash` messages behave exactly like a regular Inertia
 * form submission. Loading state is tracked per operation and endpoints
 * are derived from a configurable `basePath` (default `/persona`).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateAddressInput, PersonaId } from '../types/persona';

/** The address action currently in flight, if any. */
export type AddressAction = 'add' | 'primary' | 'delete';

export interface UseAddressesOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: AddressAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: AddressAction, errors: Record<string, string>) => void;
}

export interface UseAddressesReturn {
  /** Whether any address operation is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** The action currently in flight, or null when idle. */
  activeAction: Readonly<Ref<AddressAction | null>>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  /** Whether a specific action is currently in flight. */
  isBusy: (action: AddressAction) => boolean;
  addAddress: (payload: CreateAddressInput) => void;
  makePrimary: (addressId: PersonaId) => void;
  deleteAddress: (addressId: PersonaId) => void;
}

export function useAddresses(
  personableType: string,
  personableId: PersonaId,
  options: UseAddressesOptions = {},
): UseAddressesReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const addressesUrl = `${base}/addresses`;

  const activeAction = ref<AddressAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function isBusy(action: AddressAction): boolean {
    return activeAction.value === action;
  }

  /** Build the Inertia visit options shared by every operation. */
  function visitOptions(action: AddressAction) {
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

  function addAddress(payload: CreateAddressInput): void {
    router.post(addressesUrl, { ...payload }, visitOptions('add'));
  }

  function makePrimary(addressId: PersonaId): void {
    router.patch(`${addressesUrl}/${addressId}/primary`, {}, visitOptions('primary'));
  }

  function deleteAddress(addressId: PersonaId): void {
    router.delete(`${addressesUrl}/${addressId}`, visitOptions('delete'));
  }

  return {
    isLoading,
    activeAction: readonly(activeAction),
    errors: readonly(errors),
    isBusy,
    addAddress,
    makePrimary,
    deleteAddress,
  };
}