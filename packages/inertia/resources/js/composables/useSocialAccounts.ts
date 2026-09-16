/**
 * useSocialAccounts - a small, single-purpose composable for social account mutations.
 *
 * Every operation goes through the Inertia router so CSRF handling,
 * redirects, and `flash` messages behave exactly like a regular Inertia
 * form submission. Loading state is tracked per operation and endpoints
 * are derived from a configurable `basePath` (default `/persona`).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateSocialAccountInput, PersonaId } from '../types/persona';

/** The social account action currently in flight, if any. */
export type SocialAccountAction = 'add' | 'primary' | 'delete';

export interface UseSocialAccountsOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: SocialAccountAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: SocialAccountAction, errors: Record<string, string>) => void;
}

export interface UseSocialAccountsReturn {
  /** Whether any social account operation is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** The action currently in flight, or null when idle. */
  activeAction: Readonly<Ref<SocialAccountAction | null>>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  /** Whether a specific action is currently in flight. */
  isBusy: (action: SocialAccountAction) => boolean;
  addSocialAccount: (payload: CreateSocialAccountInput) => void;
  makePrimary: (accountId: PersonaId) => void;
  deleteSocialAccount: (accountId: PersonaId) => void;
}

export function useSocialAccounts(
  personableType: string,
  personableId: PersonaId,
  options: UseSocialAccountsOptions = {},
): UseSocialAccountsReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const accountsUrl = `${base}/social-accounts`;

  const activeAction = ref<SocialAccountAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function isBusy(action: SocialAccountAction): boolean {
    return activeAction.value === action;
  }

  /** Build the Inertia visit options shared by every operation. */
  function visitOptions(action: SocialAccountAction) {
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

  function addSocialAccount(payload: CreateSocialAccountInput): void {
    router.post(accountsUrl, { ...payload }, visitOptions('add'));
  }

  function makePrimary(accountId: PersonaId): void {
    router.patch(`${accountsUrl}/${accountId}/primary`, {}, visitOptions('primary'));
  }

  function deleteSocialAccount(accountId: PersonaId): void {
    router.delete(`${accountsUrl}/${accountId}`, visitOptions('delete'));
  }

  return {
    isLoading,
    activeAction: readonly(activeAction),
    errors: readonly(errors),
    isBusy,
    addSocialAccount,
    makePrimary,
    deleteSocialAccount,
  };
}