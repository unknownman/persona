/**
 * Profile view layer - public surface.
 *
 * Every component is a small, single-responsibility piece. Compose them
 * through `ProfileOverview` or use any sub-component directly:
 *
 *     import { ProfileOverview, ProfileContacts } from '../components/profile';
 */
export { default as ProfileOverview } from './ProfileOverview.vue';
export { default as ProfileHeader } from './ProfileHeader.vue';
export { default as ProfileContacts } from './ProfileContacts.vue';
export { default as ContactActionItem } from './ContactActionItem.vue';
export { default as ProfileAddresses } from './ProfileAddresses.vue';
export { default as AddressCard } from './AddressCard.vue';
export { default as ProfileDocuments } from './ProfileDocuments.vue';
export { default as DocumentStatusBadge } from './DocumentStatusBadge.vue';
export { default as ProfileSocials } from './ProfileSocials.vue';
export { default as SocialActivityCard } from './SocialActivityCard.vue';
export { default as ProfileRelationships } from './ProfileRelationships.vue';
export { default as ProfileAttributes } from './ProfileAttributes.vue';

export type { PersonableBag } from './ProfileOverview.vue';