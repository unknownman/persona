/**
 * usePersona - the root Persona composable.
 *
 * Stays deliberately thin: it owns the polymorphic scope and delegates every
 * domain slice to a dedicated, single-purpose composable (`useContacts` for
 * now). New slices (useAddresses, useDocuments, ...) slot in here the same
 * way instead of growing this file.
 */
import { useContacts, type UseContactsOptions, type UseContactsReturn } from './useContacts';
import type { PersonableScope, PersonaId } from '../types/persona';

export type { UseContactsOptions } from './useContacts';

export interface UsePersonaOptions extends UseContactsOptions {}

export interface UsePersonaReturn {
  /** Wire-ready polymorphic scope (`personable_type` / `personable_id`). */
  scope: PersonableScope;
  /** Dedicated contact mutations composable. */
  contacts: UseContactsReturn;
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

  const contacts = useContacts(personableType, personableId, options);

  return { scope, contacts };
}