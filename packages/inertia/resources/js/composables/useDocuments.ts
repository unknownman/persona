/**
 * useDocuments - a small, single-purpose composable for document mutations.
 *
 * Every operation goes through the Inertia router so CSRF handling,
 * redirects, and `flash` messages behave exactly like a regular Inertia
 * form submission. Loading state is tracked per operation and endpoints
 * are derived from a configurable `basePath` (default `/persona`).
 */
import { computed, readonly, ref, type ComputedRef, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateDocumentInput, PersonaId } from '../types/persona';

/** The document action currently in flight, if any. */
export type DocumentAction = 'add' | 'attach-file' | 'delete';

export interface UseDocumentsOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once an operation succeeds. */
  onSuccess?: (action: DocumentAction) => void;
  /** Called when the server returns validation errors for an operation. */
  onError?: (action: DocumentAction, errors: Record<string, string>) => void;
}

export interface UseDocumentsReturn {
  /** Whether any document operation is currently in flight. */
  isLoading: ComputedRef<boolean>;
  /** The action currently in flight, or null when idle. */
  activeAction: Readonly<Ref<DocumentAction | null>>;
  /** Field-level validation errors returned by the last operation. */
  errors: Readonly<Ref<Record<string, string>>>;
  /** Whether a specific action is currently in flight. */
  isBusy: (action: DocumentAction) => boolean;
  addDocument: (payload: CreateDocumentInput) => void;
  attachFile: (documentId: PersonaId, file: File, side?: string | null) => void;
  deleteDocument: (documentId: PersonaId) => void;
}

export function useDocuments(
  personableType: string,
  personableId: PersonaId,
  options: UseDocumentsOptions = {},
): UseDocumentsReturn {
  const { basePath = '/persona', onSuccess, onError } = options;

  const base = basePath.replace(/\/+$/, '');
  const documentsUrl = `${base}/documents`;

  const activeAction = ref<DocumentAction | null>(null);
  const errors = ref<Record<string, string>>({});
  const isLoading = computed(() => activeAction.value !== null);

  function isBusy(action: DocumentAction): boolean {
    return activeAction.value === action;
  }

  /** Build the Inertia visit options shared by every operation. */
  function visitOptions(action: DocumentAction) {
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

  function addDocument(payload: CreateDocumentInput): void {
    router.post(documentsUrl, { ...payload }, visitOptions('add'));
  }

  function attachFile(documentId: PersonaId, file: File, side?: string | null): void {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('side', side ?? 'front');

    router.post(`${documentsUrl}/${documentId}/files`, formData, {
      ...visitOptions('attach-file'),
      forceFormData: true,
    });
  }

  function deleteDocument(documentId: PersonaId): void {
    router.delete(`${documentsUrl}/${documentId}`, visitOptions('delete'));
  }

  return {
    isLoading,
    activeAction: readonly(activeAction),
    errors: readonly(errors),
    isBusy,
    addDocument,
    attachFile,
    deleteDocument,
  };
}