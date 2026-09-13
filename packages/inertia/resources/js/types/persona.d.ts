/**
 * Persona - strict TypeScript types mirroring the headless core schema.
 *
 * Naming follows the camelCase convention produced by Laravel JSON
 * (API) Resources, which the Persona API package returns by default.
 */

/**
 * Primary key of a Persona row. Supports both auto-incrementing integers
 * and string (e.g. UUID) IDs for the owning model and Persona records.
 */
export type PersonaId = number | string;

/** Any model that Persona data can be attached to via polymorphic morphs. */
export interface Personable {
  /** The morph class (or morph-map alias) of the owning model (e.g. "user"). */
  personableType: string;
  /** The primary key of the owning model. */
  personableId: PersonaId;
}

/** `persona_profiles` row. */
export interface Profile extends Personable {
  id: PersonaId;
  firstName: string | null;
  lastName: string | null;
  middleName: string | null;
  gender: string | null;
  birthDate: string | null;
  locale: string | null;
  timezone: string | null;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_contacts` row. */
export interface Contact extends Personable {
  id: PersonaId;
  type: ContactType;
  /** Decrypted contact value; never paired with the lookup hash. */
  value: string;
  isPrimary: boolean;
  isVerified: boolean;
  verifiedAt: string | null;
  isEmergency: boolean;
  createdAt: string | null;
  updatedAt: string | null;
  deletedAt: string | null;
}

/** `persona_addresses` row. */
export interface Address extends Personable {
  id: PersonaId;
  type: AddressType;
  line1: string;
  line2: string | null;
  city: string | null;
  state: string | null;
  countryCode: string | null;
  zipCode: string | null;
  isPrimary: boolean;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_documents` row. */
export interface Document extends Personable {
  id: PersonaId;
  type: DocumentType;
  /** Decrypted document number. */
  number: string;
  countryCode: string | null;
  issuedAt: string | null;
  expiresAt: string | null;
  status: DocumentStatus;
  createdAt: string | null;
  updatedAt: string | null;
  deletedAt: string | null;
}

/** `persona_social_accounts` row. */
export interface SocialAccount extends Personable {
  id: PersonaId;
  platform: SocialPlatform;
  username: string;
  url: string | null;
  isPrimary: boolean;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_physical_attributes` row. */
export interface PhysicalAttribute extends Personable {
  id: PersonaId;
  height: number | null;
  weight: number | null;
  eyeColor: string | null;
  hairColor: string | null;
  bloodType: string | null;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_legal_details` row. */
export interface LegalDetails extends Personable {
  id: PersonaId;
  nationality: string | null;
  maritalStatus: string | null;
  /** Decrypted tax identifier; never paired with the lookup hash. */
  taxId: string | null;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_relationships` row (owner side already mounted). */
export interface Relationship extends Personable {
  id: PersonaId;
  type: RelationshipType | string;
  relatedPersonableType: string;
  relatedPersonableId: PersonaId;
  createdAt: string | null;
  updatedAt: string | null;
}

/** Allowed contact vocabulary. */
export type ContactType = 'email' | 'phone' | 'handle' | 'username' | string;

/** Allowed address vocabulary. */
export type AddressType = 'home' | 'work' | 'billing' | 'shipping' | string;

/** Allowed document vocabulary (mirrors `persona.document_types`). */
export type DocumentType =
  | 'passport'
  | 'national_id'
  | 'driving_license'
  | 'birth_certificate'
  | 'residence_permit'
  | 'visa'
  | 'tax_id'
  | 'social_security'
  | string;

/** Document lifecycle status. */
export type DocumentStatus = 'pending' | 'verified' | 'rejected' | string;

/** Allowed social platform vocabulary (mirrors `persona.social_platforms`). */
export type SocialPlatform =
  | 'twitter'
  | 'linkedin'
  | 'github'
  | 'facebook'
  | 'instagram'
  | 'youtube'
  | 'tiktok'
  | 'mastodon'
  | 'discord'
  | 'threads'
  | string;

/** Allowed relationship vocabulary (mirrors `persona.relationships`). */
export type RelationshipType = 'parent' | 'child' | 'spouse' | 'sibling' | 'friend' | string;

/** Payload for creating a contact via the API. */
export interface CreateContactInput {
  type: ContactType;
  value: string;
  isPrimary?: boolean;
  isEmergency?: boolean;
}

/** Payload for creating an address via the API. */
export interface CreateAddressInput {
  type: AddressType;
  line1: string;
  line2?: string | null;
  city?: string | null;
  state?: string | null;
  countryCode?: string | null;
  zipCode?: string | null;
  isPrimary?: boolean;
}

/** Payload for creating a document via the API. */
export interface CreateDocumentInput {
  type: DocumentType;
  number: string;
  countryCode?: string | null;
  issuedAt?: string | null;
  expiresAt?: string | null;
}

/** Payload for creating a social account via the API. */
export interface CreateSocialAccountInput {
  platform: SocialPlatform;
  username: string;
  url?: string | null;
  isPrimary?: boolean;
}