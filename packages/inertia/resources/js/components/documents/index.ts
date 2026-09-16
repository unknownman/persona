/**
 * Document mutation components - public surface.
 *
 * Compose through `PersonaDocumentManager` for the full add/attach/delete
 * workflow, or use the individual atoms when you only need a subset:
 *
 *     import { PersonaDocumentManager, DocumentForm } from '../components/documents';
 */
export { default as PersonaDocumentManager } from './PersonaDocumentManager.vue';
export { default as DocumentForm } from './DocumentForm.vue';
export { default as DocumentItem } from './DocumentItem.vue';
export { default as DocumentList } from './DocumentList.vue';