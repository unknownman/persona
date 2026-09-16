/**
 * usePhysicalAttributes - a small, single-purpose composable for the
 * physical-attributes slice (a single upsert record per entity).
 *
 * Unlike the list-backed slices, physical attributes are one record per
 * personable, so the surface is a single `update` call (PUT).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreatePhysicalAttributeInput } from '../types/persona';

/** The physical-attribute action currently in flight, if any. */
export type PhysicalAttributeAction = 'update';

export interface UsePhysicalAttributesOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: PhysicalAttributeAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: PhysicalAttributeAction, errors: Record<string, string>) => void;
}

export interface UsePhysicalAttributesReturn {
  /** Whether the update is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  update: (payload: CreatePhysicalAttributeInput) => void;
}

export function usePhysicalAttributes(
  personableType: string,
  personableId: PersonaId,
  options: UsePhysicalAttributesOptions = {},
): UsePhysicalAttributesReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const url = `${base}/physical-attributes`;

  const activeAction = ref<PhysicalAttributeAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function update(payload: CreatePhysicalAttributeInput): void {
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