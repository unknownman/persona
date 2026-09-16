/**
 * useRelationships - a small, single-purpose composable for relationship mutations.
 *
 * Every operation goes through the Inertia router so CSRF handling,
 * redirects, and `flash` messages behave exactly like a regular Inertia
 * form submission. Loading state is tracked per operation and endpoints
 * are derived from a configurable `basePath` (default `/persona`).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { PersonaId, RelationshipType } from '../types/persona';

/** The relationship action currently in flight, if any. */
export type RelationshipAction = 'add' | 'delete';

export interface CreateRelationshipInput {
  /** Morph class string of the related entity, e.g. "App\\Models\\User". */
  related_type: string;
  /** Primary key of the related entity. */
  related_id: PersonaId;
  /** Relationship vocabulary, e.g. "friend", "employer". */
  type: RelationshipType | string;
}

export interface UseRelationshipsOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: RelationshipAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: RelationshipAction, errors: Record<string, string>) => void;
}

export interface UseRelationshipsReturn {
  /** Whether any relationship operation is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** The action currently in flight, or null when idle. */
  activeAction: Readonly<Ref<RelationshipAction | null>>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  /** Whether a specific action is currently in flight. */
  isBusy: (action: RelationshipAction) => boolean;
  createRelationship: (payload: CreateRelationshipInput) => void;
  deleteRelationship: (relationshipId: PersonaId) => void;
}

export function useRelationships(
  personableType: string,
  personableId: PersonaId,
  options: UseRelationshipsOptions = {},
): UseRelationshipsReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const relationshipsUrl = `${base}/relationships`;

  const activeAction = ref<RelationshipAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function isBusy(action: RelationshipAction): boolean {
    return activeAction.value === action;
  }

  /** Build the Inertia visit options shared by every operation. */
  function visitOptions(action: RelationshipAction) {
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

  function createRelationship(payload: CreateRelationshipInput): void {
    router.post(relationshipsUrl, { ...payload }, visitOptions('add'));
  }

  function deleteRelationship(relationshipId: PersonaId): void {
    router.delete(`${relationshipsUrl}/${relationshipId}`, visitOptions('delete'));
  }

  return {
    isLoading,
    activeAction: readonly(activeAction),
    errors: readonly(errors),
    isBusy,
    createRelationship,
    deleteRelationship,
  };
}