# Laravel Persona: Blade

This package is a presentation-layer companion for the [Laravel Persona](https://github.com/unknownman/persona) ecosystem.

**Laravel Persona** is a powerful, headless, and polymorphic data layer for managing person-related data (profiles, contacts, addresses, documents, social accounts, and relationships) in Laravel applications.

## What is this package?

This package provides a collection of Tailwind CSS–based Blade components that render Persona data as rich, responsive UI sections. Drop a `<x-persona-profile-overview>` component into any view and instantly display a full profile with contacts, addresses, documents, relationships, and social accounts.

## Installation

This package requires the Persona Core. You can install both via Composer:

```bash
composer require laravel-persona/core laravel-persona/blade
```

Next, run the interactive installer to publish the assets:

```bash
php artisan persona:install
```

## Full Documentation

Please refer to the **[Main Repository](https://github.com/unknownman/persona)** for complete installation instructions, API usage, and configuration details.

---
*Note: This repository is a read-only split of the main monorepo. Please submit all issues and pull requests to the [main repository](https://github.com/unknownman/persona).*
