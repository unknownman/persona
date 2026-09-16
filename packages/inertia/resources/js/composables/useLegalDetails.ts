/**
 * useLegalDetails - a small, single-purpose composable for the legal-details
 * slice (a single upsert record per entity).
 *
 * Unlike the list-backed slices, legal details are one record per personable,
 * so the surface is a single `update` call (PUT).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateLegalDetailInput, PersonaId } from '../types/persona';

/** The legal-detail action currently in flight, if any. */
export type LegalDetailAction = 'update';

export interface UseLegalDetailsOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: LegalDetailAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: LegalDetailAction, errors: Record<string, string>) => void;
}

export interface UseLegalDetailsReturn {
  /** Whether the update is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  update: (payload: CreateLegalDetailInput) => void;
}

export function useLegalDetails(
  personableType: string,
  personableId: PersonaId,
  options: UseLegalDetailsOptions = {},
): UseLegalDetailsReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const url = `${base}/legal-details`;

  const activeAction = ref<LegalDetailAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function update(payload: CreateLegalDetailInput): void {
    router.put(url, { ...payload }, {
      onStart: () => {
        activeAction.value = 'update';
        errors.value = {};
      },
      onFinish: () => {
        activeAction.value = null;
      },
      onSuccess: () => onSuccess?.('update'),
      onError: (fieldErrors: Record<string, string>) => {
        errors.value = fieldErrors;
        onError?.('update', fieldErrors);
      },
    });
  }

  return {
    isLoading,
    errors: readonly(errors),
    update,
  };
}