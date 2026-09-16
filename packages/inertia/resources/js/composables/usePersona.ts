/**
 * usePersona - the root Persona composable.
 *
 * Stays deliberately thin: it owns the polymorphic scope and delegates every
 * domain slice to a dedicated, single-purpose composable (`useContacts`,
 * `useAddresses`, `useDocuments`, `useSocialAccounts`, `useRelationships`,
 * `usePhysicalAttributes`, `useLegalDetails`). New slices slot in here the
 * same way instead of growing this file.
 */
import { useContacts, type UseContactsReturn } from './useContacts';
import { useAddresses, type UseAddressesReturn } from './useAddresses';
import { useDocuments, type UseDocumentsReturn } from './useDocuments';
import { useSocialAccounts, type UseSocialAccountsReturn } from './useSocialAccounts';
import { useRelationships, type UseRelationshipsReturn } from './useRelationships';
import { usePhysicalAttributes, type UsePhysicalAttributesReturn } from './usePhysicalAttributes';
import { useLegalDetails, type UseLegalDetailsReturn } from './useLegalDetails';
import type { PersonableScope, PersonaId } from '../types/persona';

export interface UsePersonaOptions {
  /** Base path the endpoints are derived from. Defaults to "/persona". */
  basePath?: string;
  /** Called once any mutation succeeds. */
  onSuccess?: () => void;
  /** Called when the server returns validation errors for any mutation. */
  onError?: (errors: Record<string, string>) => void;
}

export interface UsePersonaReturn {
  /** Wire-ready polymorphic scope (`personable_type` / `personable_id`). */
  scope: PersonableScope;
  /** Dedicated contact mutations composable. */
  contacts: UseContactsReturn;
  /** Dedicated address mutations composable. */
  addresses: UseAddressesReturn;
  /** Dedicated document mutations composable. */
  documents: UseDocumentsReturn;
  /** Dedicated social account mutations composable. */
  socialAccounts: UseSocialAccountsReturn;
  /** Dedicated relationship mutations composable. */
  relationships: UseRelationshipsReturn;
  /** Dedicated physical-attributes upsert composable. */
  physicalAttributes: UsePhysicalAttributesReturn;
  /** Dedicated legal-details upsert composable. */
  legalDetails: UseLegalDetailsReturn;
}

/** Alias of UsePersonaReturn for ergonomic destructuring. */
export type PersonaStore = UsePersonaReturn;

export function usePersona(
  personableType: string,
  personableId: PersonaId,
  options: UsePersonaOptions = {},
): UsePersonaReturn {
  const scope: PersonableScope = {
    personable_type: personableType,
    personable_id: personableId,
  };

  const { basePath, onSuccess, onError } = options;

  const sharedSuccess = () => onSuccess?.();

  const contacts = useContacts(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const addresses = useAddresses(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const documents = useDocuments(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const socialAccounts = useSocialAccounts(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const relationships = useRelationships(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const physicalAttributes = usePhysicalAttributes(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  const legalDetails = useLegalDetails(personableType, personableId, {
    basePath,
    onSuccess: sharedSuccess,
    onError: (_, fieldErrors) => onError?.(fieldErrors),
  });

  return {
    scope,
    contacts,
    addresses,
    documents,
    socialAccounts,
    relationships,
    physicalAttributes,
    legalDetails,
  };
}