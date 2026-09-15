# Laravel Persona: Livewire

This package is a presentation-layer companion for the [Laravel Persona](https://github.com/unknownman/persona) ecosystem.

**Laravel Persona** is a powerful, headless, and polymorphic data layer for managing person-related data (profiles, contacts, addresses, documents, social accounts, and relationships) in Laravel applications.

## What is this package?

This package ships a set of production-ready Livewire 3 components for managing Persona data directly from the browser. Drop a component into any Blade view and get a full contact manager and profile overview with validation, ownership guards, and live computed properties — no custom wiring required.

## Installation

This package requires the Persona Core. You can install both via Composer:

```bash
composer require laravel-persona/core laravel-persona/livewire
```

Next, run the interactive installer to publish the assets:

```bash
php artisan persona:install
```

## Full Documentation

Please refer to the **[Main Repository](https://github.com/unknownman/persona)** for complete installation instructions, API usage, and configuration details.

---
*Note: This repository is a read-only split of the main monorepo. Please submit all issues and pull requests to the [main repository](https://github.com/unknownman/persona).*
