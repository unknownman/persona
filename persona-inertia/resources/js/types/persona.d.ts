/**
 * Persona - strict TypeScript types mirroring the headless core schema.
 *
 * Naming follows the camelCase convention produced by Laravel JSON
 * (API) Resources, which the Persona API package returns by default.
 */

/** Any model that Persona data can be attached to via polymorphic morphs. */
export interface Personable {
  /** The morph class of the owning model (e.g. "App\\Models\\User"). */
  personableType: string;
  /** The primary key of the owning model. */
  personableId: number;
}

/** `persona_profiles` row. */
export interface Profile extends Personable {
  id: number;
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
  id: number;
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

/** `persona_documents` row. */
export interface Document extends Personable {
  id: number;
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
  id: number;
  platform: SocialPlatform;
  username: string;
  url: string | null;
  isPrimary: boolean;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_physical_attributes` row. */
export interface PhysicalAttribute extends Personable {
  id: number;
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
  id: number;
  nationality: string | null;
  maritalStatus: string | null;
  /** Decrypted tax identifier; never paired with the lookup hash. */
  taxId: string | null;
  createdAt: string | null;
  updatedAt: string | null;
}

/** `persona_relationships` row (owner side already mounted). */
export interface Relationship extends Personable {
  id: number;
  type: RelationshipType | string;
  relatedPersonableType: string;
  relatedPersonableId: number;
  createdAt: string | null;
  updatedAt: string | null;
}

/** Allowed contact vocabulary. */
export type ContactType = 'email' | 'phone' | 'handle' | 'username' | string;

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

/** Payload for creating a document via the API. */
export interface CreateDocumentInput {
  type: DocumentType;
  number: string;
  countryCode?: string | null;
  issuedAt?: string | null;
  expiresAt?: string | null;
}