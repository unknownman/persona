/**
 * Contact components - public surface.
 *
 * Compose through `PersonaContactManager` for the full add/verify/primary/
 * delete workflow, or use the individual atoms when you only need a subset:
 *
 *     import { PersonaContactManager, ContactForm, ContactBadge } from '../components/contacts';
 */
export { default as PersonaContactManager } from './PersonaContactManager.vue';
export { default as ContactForm } from './ContactForm.vue';
export { default as ContactItem } from './ContactItem.vue';
export { default as ContactList } from './ContactList.vue';
export { default as ContactBadge } from './ContactBadge.vue';