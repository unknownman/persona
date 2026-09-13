<p align="center">
  <img src="assets/Logo/logo.svg" alt="Persona" width="75%">
</p>

# Persona

**Persona** is a headless Laravel package that provides a complete data layer for person-related data — profiles, social accounts, contacts, addresses, documents, physical attributes, legal details, and relationships — built on polymorphic relations.

Strictly **headless**: no controllers, routes, views, or policies. Persona only ships the data layer (models, migrations, managers, contracts, events) so your application owns the UI and logic around it.

## Features

- **Polymorphic by design** — attach person data to any model, not just a single `users` table.
- **Nine vocabulary tables** — profiles, social accounts, contacts, addresses, documents, document files, physical attributes, legal details, and relationships.
- **Directed & symmetric relationships** — e.g. `parent`/`child` are directed; `spouse`/`sibling` are symmetric.
- **Config-driven vocabulary** — document types, address types, social platforms, and relationship types are defined in `config/persona.php`.
- **Model overrides** — swap any default model for your own via config.
- **Secure hashing** — requires a unique `PERSONA_HASH_KEY` to secure sensitive hashes; the provider refuses to boot without it.

## Installation

```bash
composer require laravel-persona/core
```

Add your hash key to `.env`:

```dotenv
PERSONA_HASH_KEY=your-unique-secret-key
```

The package refuses to boot until `PERSONA_HASH_KEY` is set.

## Configuration

Publish the configuration:

```bash
php artisan vendor:publish --tag=persona-config
```

## License

MIT