<p align="center">
    <img src="assets/Logo/logo.svg" alt="Laravel Persona" width="400">
</p>

<p align="center">
    <a href="https://packagist.org/packages/laravel-persona/core"><img src="https://img.shields.io/packagist/v/laravel-persona/core.svg?style=flat-square&v=1.0.2" alt="Latest Version on Packagist"></a>
    <a href="https://packagist.org/packages/laravel-persona/core"><img src="https://img.shields.io/packagist/dt/laravel-persona/core.svg?style=flat-square&v=1.0.2" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel-persona/core"><img src="https://img.shields.io/packagist/l/laravel-persona/core.svg?style=flat-square&v=1.0.2" alt="License"></a>
</p>

## Introduction

Laravel Persona provides an expressive, fluent interface to manage person-related data within your application. Whether you are building an identity platform, a CRM, or a comprehensive user profile system, Persona handles the heavy lifting of managing profiles, contacts, addresses, documents, physical attributes, social accounts, and relationships.

At its core, Persona is strictly **headless** and built on polymorphic relationships, meaning it can be attached to any Eloquent model (`User`, `Customer`, `Employee`). It also ships with optional first-party companion packages for Livewire, Inertia, and Blade, allowing you to drop a beautiful, atomic UI directly into your stack.

## Installation

Persona is structured as a monorepo. You should install the `core` package, alongside any presentation layer companion packages that match your application's stack:

```bash
# Core only
composer require laravel-persona/core

# Core + Livewire UI components
composer require laravel-persona/core laravel-persona/livewire

# Core + Inertia/Vue components
composer require laravel-persona/core laravel-persona/inertia
```

After requiring the packages, run the Persona interactive installer. This command will intelligently detect your installed companion packages and publish the necessary assets, configuration, and migrations:

```bash
php artisan persona:install
```

### Configuration

Persona seamlessly secures sensitive data (such as document numbers and contact values) using HMAC-SHA256 hashing. By default, Persona uses your application's `APP_KEY`. However, for maximum security and key-rotation capabilities, you may define a dedicated Persona hash key in your `.env` file:

```dotenv
PERSONA_HASH_KEY=your-unique-secret-key
```

## Basic Usage

### The `HasPersona` Trait

To get started, add the `Persona\Traits\HasPersona` trait to your model. This trait provides the necessary polymorphic relationships and the fluent `persona()` manager instance:

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Persona\Traits\HasPersona;

class User extends Authenticatable
{
    use HasPersona;
}
```

### The Fluent API

Persona's API is designed to be highly expressive. All mutations enforce database invariants, handle soft-delete restorations automatically, and ensure strict domain-level ownership.

```php
// Managing contacts...
$user->persona()->addContact('phone', '+1234567890', isPrimary: true);
$user->persona()->verifyContact($contact, $otpCode);

// Updating profile data...
$user->persona()->updateProfile([
    'first_name' => 'Taylor',
    'last_name' => 'Otwell',
    'timezone' => 'America/Chicago',
]);

// Attaching documents...
$document = $user->persona()->addDocument('passport', 'A1234567', [
    'country_code' => 'US'
]);

// Connecting relationships (Canonical Ordering handled automatically)...
$user->persona()->linkTo($otherUser, 'friend');
```

> **Note:** Every mutation runs within a database transaction and dispatches domain events implementing `ShouldDispatchAfterCommit`, ensuring your application's event listeners only receive valid, persisted data.

### The Persona Footprint

When rendering a comprehensive profile, querying multiple related tables can introduce N+1 performance issues. Persona solves this via the `getFootprint` method, which intelligently aggregates and eager-loads all Persona data into a single, well-typed array:

```php
$footprint = $user->persona()->getFootprint();

echo $footprint['profile']->first_name;
$contacts = $footprint['contacts'];
$documents = $footprint['documents'];
```

## Presentation Layer (Companions)

If you installed one of Persona's companion packages, rendering a complete, interactive profile requires only a single line of code. The presentation layers automatically consume the `getFootprint` method to guarantee optimal database performance.

### Livewire

If you are using Livewire 3, you may render the profile overview component directly in your Blade templates:

```blade
<livewire:persona.profile-overview :personable="$user" />
```

### Inertia (Vue 3)

If you installed the Inertia companion, Persona provides a complete set of strictly typed Vue 3 components and composables. You may pass the hydrated footprint to the `ProfileOverview` component:

```vue
<script setup lang="ts">
import { ProfileOverview } from '@/persona/components/profile';
import type { PersonableBag, Relationship, PersonableScope } from '@/persona/types/persona';

defineProps<{
    personable: PersonableBag;
    relationships: Relationship[];
    personaScope: PersonableScope;
}>();
</script>

<template>
    <ProfileOverview 
        :personable="personable" 
        :relationships="relationships" 
        :persona-scope="personaScope" 
    />
</template>
```

## Security & Data Integrity

### Hashing and Encryption

Persona takes PII (Personally Identifiable Information) seriously. Lookup columns (`value_hash`, `number_hash`) are hashed using `PersonaHasher` to prevent plain-text exposure in the database while maintaining the ability to enforce unique SQL constraints. Furthermore, sensitive values are automatically encrypted at rest using Laravel's native encryption.

### Erasing Data (GDPR)

If you need to implement a "Delete My Account" or a right-to-be-forgotten feature, Persona provides a nuclear option. The `forgetAll` method will permanently delete every row across all 9 Persona tables and remove associated physical files from your storage disks:

```php
$user->persona()->forgetAll();
```

### Data Cleanup & Orphaned Records

Persona rows attach to their owner through a **polymorphic morph pair** (`personable_type` / `personable_id`). Because the target table is unknown at the schema level, the database **cannot enforce a foreign key** — nothing stops a persona row from outliving the User, Customer, or Employee it belonged to. Persona mitigates this structural RDBMS limitation at two layers:

**Automated cleanup on Eloquent deletion.** The `HasPersona` trait registers a `deleting` hook that wipes the full Persona footprint whenever the owner is truly removed:

```php
$user->delete();
```

For models using the `SoftDeletes` trait there is a deliberate difference:

| Call | Behavior |
| --- | --- |
| `$user->delete()` | **Soft delete.** The row is flagged `deleted_at` and still exists, so Persona data is **preserved** and restored together with the model. |
| `$user->forceDelete()` | **Hard delete.** The row is removed for real, so the Persona footprint is **wiped automatically**. |

**Sweeping hard orphans with artisan.** Model events only cover graceful deletes. Owners can still disappear through mass `Model::where(...)->delete()` queries, DB-level cascades, or raw SQL. The built-in sweeper removes rows whose polymorphic parent no longer exists:

```bash
php artisan persona:clean-orphans
php artisan persona:clean-orphans --pretend   # preview only, delete nothing
```

The sweeper is **enterprise-ready**: instead of one massive `DELETE`, it collects orphaned rows as ascending IDs and deletes them in **batches of 1,000** (`persona.cleanup.chunk_size`, or the `--chunk` option), so table locks stay short-lived and memory stays flat even on tables with millions of orphans:

```bash
php artisan persona:clean-orphans --chunk=2500
```

It resolves each distinct `personable_type` to its backing table, sweeps both sides of `persona_relationships`, and spares soft-deleted parents. Schedule it nightly from `routes/console.php`:

```php
Schedule::command('persona:clean-orphans')->dailyAt('03:00');
```

### Preserving Data on Deletion

Sometimes a deleted entity's Persona data must live on — audit trails, compliance, or reassigning a profile to a future record. Deleting a Customer or Employee is not always a request to forget everything about them.

Opt out of the automatic wipe **per model** with a single expressive property:

```php
use Persona\Traits\HasPersona;

class Customer extends Model
{
    use HasPersona;

    // Retain Persona data even if the Customer record is deleted
    public bool $preservePersonaOnDelete = true;
}
```

With this flag set:

- `$customer->delete()` and `$customer->forceDelete()` leave every profile, contact, address, document, and relationship **intact** in the database. You can later re-attach the data to a fresh record by creating it and assigning the old `personable_id`.
- The `persona:clean-orphans` sweeper **respects the flag too** — it discovers the host model for each distinct `personable_type`, and when preservation is enabled it skips that type entirely:
  ```bash
  php artisan persona:clean-orphans
  # Skipping [Customer] (Preservation enabled)
  ```
- Alternatively, provide a `preservePersonaOnDelete()` method for dynamic, runtime decisions (e.g. retaining data only for legal entities). Preservation is enabled whenever **either** the property or the method returns `true`.

## License

Laravel Persona is open-sourced software licensed under the [MIT license](LICENSE.md).
```