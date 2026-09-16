/**
 * Relationship mutation components - public surface.
 *
 * Compose through `PersonaRelationshipManager` for the full add/delete
 * workflow, or use the individual atoms when you only need a subset:
 *
 *     import { PersonaRelationshipManager, RelationshipForm } from '../components/relationships';
 */
export { default as PersonaRelationshipManager } from './PersonaRelationshipManager.vue';
export { default as RelationshipForm } from './RelationshipForm.vue';
export { default as RelationshipItem } from './RelationshipItem.vue';
export { default as RelationshipList } from './RelationshipList.vue';