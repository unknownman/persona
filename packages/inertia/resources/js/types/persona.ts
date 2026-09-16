/**
 * Persona - strict TypeScript types mirroring the headless core schema.
 *
 * Key casing is snake_case to exactly match what `packages/api` JSON
 * Resources return (Laravel resources serialize column names verbatim)
 * and what the Inertia controller stub ships as page props.
 */

/** Primary key of a Persona row. Supports integer and string (e.g. UUID) IDs. */
export type PersonaId = number | string;

/** The polymorphic owner every Persona row is mounted on. Wire-ready for query params. */
export interface PersonableScope {
  personable_type: string;
  personable_id: PersonaId;
}

/** Base contract: every Persona row carries its polymorphic owner. */
export interface Personable extends PersonableScope {}

/** `persona_profiles` row, as serialized by ProfileResource. */
export interface Profile extends Personable {
  id: PersonaId;
  first_name: string | null;
  last_name: string | null;
  middle_name: string | null;
  gender: string | null;
  birth_date: string | null;
  locale: string | null;
  timezone: string | null;
  avatar_url?: string | null;
  created_at: string | null;
  updated_at: string | null;
}

/** `persona_contacts` row, as serialized by ContactResource. */
export interface Contact extends Personable {
  id: PersonaId;
  type: ContactType;
  /** Decrypted contact value. The lookup hash is never exposed. */
  value: string;
  is_primary: boolean;
  is_verified: boolean;
  verified_at: string | null;
  is_emergency: boolean;
  created_at: string | null;
  updated_at: string | null;
  deleted_at: string | null;
}

/** `persona_addresses` row, as serialized by AddressResource. */
export interface Address extends Personable {
  id: PersonaId;
  type: AddressType;
  line_1: string;
  line_2: string | null;
  city: string | null;
  state: string | null;
  country_code: string | null;
  zip_code: string | null;
  is_primary: boolean;
  created_at: string | null;
  updated_at: string | null;
}

/** `persona_documents` row, as serialized by DocumentResource. */
export interface Document extends Personable {
  id: PersonaId;
  type: DocumentType;
  /** Decrypted document number. The lookup hash is never exposed. */
  number: string;
  country_code: string | null;
  issued_at: string | null;
  expires_at: string | null;
  status: DocumentStatus;
  created_at: string | null;
  updated_at: string | null;
  deleted_at: string | null;
}

/** `persona_document_files` row (does not have its own published Resource yet). */
export interface DocumentFile {
  id: PersonaId;
  document_id: PersonaId;
  file_path: string;
  disk: string;
  side: string | null;
  created_at: string | null;
  updated_at: string | null;
}

/** `persona_social_accounts` row, as serialized by SocialAccountResource. */
export interface SocialAccount extends Personable {
  id: PersonaId;
  platform: SocialPlatform;
  username: string;
  url: string | null;
  is_primary: boolean;
  created_at: string | null;
  updated_at: string | null;
}

/**
 * A recent public post / activity resolved for a social account.
 *
 * Produced by the backend `SocialActivityResolverContract` so the UI never
 * talks to third-party APIs directly. Hosts swap the resolver in the
 * container and the views render whatever comes back.
 */
export interface SocialActivity {
  /** External permalink to the original post/content. */
  url: string;
  /** ISO-8601 publish timestamp, when the resolver provides one. */
  published_at?: string;
  /** Plain-text snippet of the post body, when available. */
  text?: string;
}

/** `persona_physical_attributes` row, as serialized by PhysicalAttributeResource. */
export interface PhysicalAttribute extends Personable {
  id: PersonaId;
  height: number | null;
  weight: number | null;
  eye_color: string | null;
  hair_color: string | null;
  blood_type: string | null;
  created_at: string | null;
  updated_at: string | null;
}

/** `persona_legal_details` row, as serialized by LegalDetailResource. */
export interface LegalDetail extends Personable {
  id: PersonaId;
  nationality: string | null;
  marital_status: string | null;
  /** Decrypted tax identifier. The lookup hash is never exposed. */
  tax_id: string | null;
  created_at: string | null;
  updated_at: string | null;
}

/** `persona_relationships` row (owner side mounted), as serialized by RelationshipResource. */
export interface Relationship extends Personable {
  id: PersonaId;
  type: RelationshipType | string;
  related_personable_type: string;
  related_personable_id: PersonaId;
  created_at: string | null;
  updated_at: string | null;
}

/** Union of every Persona entity, for generic list/table components. */
export type PersonaEntity =
  | Profile
  | Contact
  | Address
  | Document
  | DocumentFile
  | SocialAccount
  | PhysicalAttribute
  | LegalDetail
  | Relationship;

/**
 * Allowed contact vocabulary (mirrors `persona.normalizers` / contact types).
 * The `(string & {})` intersection keeps IDE autocompletion while still
 * accepting custom values added by the host application.
 */
export type ContactType = 'email' | 'phone' | 'handle' | 'username' | (string & {});

/** Allowed address vocabulary (mirrors `persona.address_types`). */
export type AddressType =
  | 'home'
  | 'work'
  | 'billing'
  | 'shipping'
  | 'mailing'
  | 'temporary'
  | 'permanent'
  | (string & {});

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
  | (string & {});

/**
 * Document lifecycle status (mirrors `persona.document_statuses`).
 *
 * NOTE: these literals are only informational suggestions. The backend reads
 * the actual vocabulary from `persona.document_statuses.*` (initial/verified/
 * rejected), which hosts may rename freely. Keep this union in sync with that
 * config; the `(string & {})` intersection accepts any host-customized value.
 */
export type DocumentStatus = 'pending' | 'verified' | 'rejected' | (string & {});

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
  | (string & {});

/** Allowed relationship vocabulary (mirrors `persona.relationships`). */
export type RelationshipType =
  | 'parent'
  | 'child'
  | 'guardian'
  | 'dependent'
  | 'employer'
  | 'employee'
  | 'spouse'
  | 'sibling'
  | 'friend'
  | 'partner'
  | 'relative'
  | 'colleague'
  | (string & {});

/** Payload for creating a contact (snake_case to match backend validation). */
export interface CreateContactInput {
  type: ContactType;
  value: string;
  is_primary?: boolean;
  is_emergency?: boolean;
}

/** Payload for verifying a contact via its one-time password. */
export interface VerifyOtpInput {
  otp: string;
}

/** Payload for creating a profile. */
export interface CreateProfileInput {
  first_name?: string | null;
  last_name?: string | null;
  middle_name?: string | null;
  gender?: string | null;
  birth_date?: string | null;
  locale?: string | null;
  timezone?: string | null;
}

/** Payload for creating an address. */
export interface CreateAddressInput {
  type: AddressType;
  line_1: string;
  line_2?: string | null;
  city?: string | null;
  state?: string | null;
  country_code?: string | null;
  zip_code?: string | null;
  is_primary?: boolean;
}

/** Payload for creating a document. */
export interface CreateDocumentInput {
  type: DocumentType;
  number: string;
  country_code?: string | null;
  issued_at?: string | null;
  expires_at?: string | null;
}

/** Payload for creating a social account. */
export interface CreateSocialAccountInput {
  platform: SocialPlatform;
  username: string;
  url?: string | null;
  is_primary?: boolean;
}

/** Payload for creating/updating physical attributes. */
export interface CreatePhysicalAttributeInput {
  height?: number | null;
  weight?: number | null;
  eye_color?: string | null;
  hair_color?: string | null;
  blood_type?: string | null;
}

/** Payload for creating/updating legal details. */
export interface CreateLegalDetailInput {
  nationality?: string | null;
  marital_status?: string | null;
  tax_id?: string | null;
}