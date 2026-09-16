/**
 * Social account mutation components - public surface.
 *
 * Compose through `PersonaSocialAccountManager` for the full
 * add/primary/delete workflow, or use the individual atoms when you only
 * need a subset:
 *
 *     import { PersonaSocialAccountManager, SocialAccountForm } from '../components/social-accounts';
 */
export { default as PersonaSocialAccountManager } from './PersonaSocialAccountManager.vue';
export { default as SocialAccountForm } from './SocialAccountForm.vue';
export { default as SocialAccountItem } from './SocialAccountItem.vue';
export { default as SocialAccountList } from './SocialAccountList.vue';