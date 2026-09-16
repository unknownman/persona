# Laravel Persona: Inertia

Presentation-layer companion for [Laravel Persona](https://github.com/unknownman/persona): a publishable Inertia controller, a typed Vue 3 component set, and single-purpose composables for managing person-related data.

**Status: work-in-progress.** This package is actively maintained but should not be treated as a finished product. It ships a real, usable surface (below) — and nothing more. If a feature is not listed, it does not exist yet; do not assume it is hidden behind undocumented configuration.

## What ships

### Backend (publishable, ownership-guarded)

- `PersonaController` stub (`php artisan vendor:publish --tag=persona-inertia-controllers`): profile overview, plus create / primary / delete handlers for contacts, addresses, social accounts, relationships, and documents (including per-side file attach and status verification). Every mutation validates ownership against the resolved personable before acting.
- Route file `routes/persona.php` (`--tag=persona-inertia-routes`): 15 named routes under `/persona`, `auth` + `verified` middleware, `{personable}` resolved via a shared `PersonaResource`/scope.
- The controller renders the `Profile` page via Inertia (`--tag=persona-inertia-page`).

### Frontend (publishable, typed)

Published as TS sources with a complete type surface in `resources/js/types/persona.ts` — one interface per Persona entity and input payload, so host apps and this package share the same shapes.

- **Composables** (`@laravel-persona/inertia/composables`): `usePersona` aggregate plus one slice per domain — `useContacts`, `useAddresses`, `useDocuments`, `useSocialAccounts`, `useRelationships`, `usePhysicalAttributes`, `useLegalDetails`. Each is a thin wrapper over the Inertia router; endpoints derive from a configurable `basePath` (default `/persona`).
- **Read-only components** (`.../components/profile`): `ProfileOverview`, `PersonaBadge` and profile detail atoms.
- **Mutation components**:
  - `.../components/contacts` — `PersonaContactManager`, `ContactForm`, `ContactItem`, `ContactList`, `ContactBadge`.
  - `.../components/addresses` — `PersonaAddressManager`, `AddressForm`, `AddressItem`, `AddressList`.
  - `.../components/documents` — `PersonaDocumentManager`, `DocumentForm`, `DocumentItem`, `DocumentList`.
  - `.../components/social-accounts` — `PersonaSocialAccountManager`, `SocialAccountForm`, `SocialAccountItem`, `SocialAccountList`.
  - `.../components/relationships` — `PersonaRelationshipManager`, `RelationshipForm`, `RelationshipItem`, `RelationshipList`.
  - `.../components/attributes` — `PersonaAttributesManager`, `PhysicalAttributeForm`, `LegalDetailForm`.

Atoms are presentational (they emit typed intents), managers own orchestration (composable + `updated` event), and no component talks to the backend directly.

## Not included (yet)

- Styling. Components ship unstyled with stable BEM-ish class names, exactly like the rest of the ecosystem.
- Automatic routing/pages beyond the single published `Profile` page — hosts mount the managers wherever they like.
- Every Persona Core feature surface (e.g. per-file display resources, social activity UI).

## Installation

```bash
composer require laravel-persona/core laravel-persona/inertia
php artisan persona:install
```

If you prefer to publish only the pieces above:

```bash
php artisan vendor:publish --provider="Persona\Inertia\Providers\PersonaInertiaServiceProvider"
```

## Full Documentation

See the **[Main Repository](https://github.com/unknownman/persona)** for API and configuration details.

---
*Note: This repository is a read-only split of the main monorepo. Please submit all issues and pull requests to the [main repository](https://github.com/unknownman/persona).*