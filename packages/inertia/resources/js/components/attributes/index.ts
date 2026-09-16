/**
 * Attribute mutation components - public surface.
 *
 * Compose through `PersonaAttributesManager` for both single-record upserts
 * (physical attributes + legal details), or use each form on its own:
 *
 *     import { PersonaAttributesManager, PhysicalAttributeForm } from '../components/attributes';
 */
export { default as PersonaAttributesManager } from './PersonaAttributesManager.vue';
export { default as PhysicalAttributeForm } from './PhysicalAttributeForm.vue';
export { default as LegalDetailForm } from './LegalDetailForm.vue';