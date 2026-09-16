/**
 * Persona composables - public surface.
 *
 * Every composable is a small, single-purpose slice over the Inertia router.
 * Reach for the aggregate `usePersona` in a page, or a specific composable
 * when you only need one slice:
 *
 *     import { usePersona } from '../composables';
 *     import { useDocuments } from '../composables';
 */
export { usePersona } from './usePersona';
export type { UsePersonaReturn, UsePersonaOptions, PersonaStore } from './usePersona';

export { useContacts } from './useContacts';
export type { UseContactsReturn, UseContactsOptions, ContactAction } from './useContacts';

export { useAddresses } from './useAddresses';
export type { UseAddressesReturn, UseAddressesOptions, AddressAction } from './useAddresses';

export { useDocuments } from './useDocuments';
export type { UseDocumentsReturn, UseDocumentsOptions, DocumentAction } from './useDocuments';

export { useSocialAccounts } from './useSocialAccounts';
export type {
  UseSocialAccountsReturn,
  UseSocialAccountsOptions,
  SocialAccountAction,
} from './useSocialAccounts';

export { useRelationships } from './useRelationships';
export type {
  UseRelationshipsReturn,
  UseRelationshipsOptions,
  CreateRelationshipInput,
  RelationshipAction,
} from './useRelationships';

export { usePhysicalAttributes } from './usePhysicalAttributes';
export type {
  UsePhysicalAttributesReturn,
  UsePhysicalAttributesOptions,
  PhysicalAttributeAction,
} from './usePhysicalAttributes';

export { useLegalDetails } from './useLegalDetails';
export type { UseLegalDetailsReturn, UseLegalDetailsOptions, LegalDetailAction } from './useLegalDetails';

export { useProfile } from './useProfile';
export type { UseProfileReturn } from './useProfile';