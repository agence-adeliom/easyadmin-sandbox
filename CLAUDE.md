# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony 7 EasyAdmin sandbox application showcasing Adeliom's Easy* bundle ecosystem. It's a monorepo containing multiple Symfony bundles as local packages in the `lib/` directory, managed with monorepo-builder.

## Development Environment Setup

This project uses DDEV for local development:

```bash
# Start the project
ddev start

# Install PHP dependencies
ddev composer install

# Run database migrations
ddev console doc:mig:mig

# Load fixtures
ddev console doctrine:fixtures:load -q

# Install Node.js dependencies
ddev npm install
```

## Key Commands

### Development
- `ddev npm run dev` - Build assets for development
- `ddev npm run watch` - Watch for asset changes
- `ddev npm run build` - Build production assets
- `ddev symfony serve` - Alternative Symfony server

### Testing
```bash
# Prepare test environment
ddev console d:m:m --env=test -n
ddev console doc:fixtures:load --env=test -n

# Run tests
ddev composer unit-tests
# or directly
ddev php bin/phpunit
```

### Code Quality
- `ddev lint-back` - Run PHP linting
- `ddex exec vendor/bin/phpcs` - PHP CodeSniffer (PSR-12)
- `ddex exec vendor/bin/phpstan` - Static analysis (level 6)
- `ddex exec vendor/bin/rector` - Code modernization

### Monorepo Management

This project uses symplify/monorepo-builder to manage packages in `lib/`:

```bash
# Dry run a patch release
ddex exec vendor/bin/monorepo-builder release patch --dry-run

# Execute patch release
ddex exec vendor/bin/monorepo-builder release patch
```

## Architecture Overview

### Monorepo Structure
- **`lib/`** - Contains Adeliom's Easy* bundles as local packages
- **`src/`** - Main application code demonstrating bundle usage
- **`_recipes/`** - Symfony Flex recipes for the bundles

### Key Bundles (lib/)
- **easy-admin-user-bundle** - User management for EasyAdmin
- **easy-block-bundle** - Block system for content management
- **easy-blog-bundle** - Blog functionality with categories and posts
- **easy-config-bundle** - Dynamic configuration management
- **easy-editor-bundle** - Block-based content editor
- **easy-faq-bundle** - FAQ system with categories
- **easy-fields-bundle** - Custom form field types
- **easy-media-bundle** - Media management system
- **easy-menu-bundle** - Menu management
- **easy-page-bundle** - Page management
- **easy-redirect-bundle** - URL redirection management
- **easy-seo-bundle** - SEO tools and meta management

### Application Structure
- **Controllers** - Admin controllers in `src/Controller/Admin/`, page controllers in `src/Controller/EasyPage/`
- **Entities** - Organized by bundle in `src/Entity/Easy*/`
- **Message Handlers** - Async processing for media operations

### Frontend Assets
- **Webpack Encore** for asset compilation
- **Stimulus** for JavaScript interactivity
- Multiple bundles have their own asset compilation (webpack.mix.js, package.json)

## Development Patterns

### Entity Organization
Entities are organized by their respective bundles (e.g., `src/Entity/EasyBlog/`, `src/Entity/EasyMedia/`). Each bundle follows consistent patterns with repositories, controllers, and admin configurations.

### Block System
The project uses two block systems:
1. **EasyEditor** - Simple block-based editor

### Testing Strategy
- **Unit tests** for individual bundle functionality in `tests/`
- **Integration tests** for bundle interactions
- **Fixtures** for test data in `src/DataFixtures/`

### Multi-Environment Support
- **Development** - DDEV with MariaDB 10.4, PHP 8.2
- **Test** - SQLite database for fast testing
- **Production** - Configured via environment variables

## Database

- **Development**: MariaDB 10.4 via DDEV
- **Test**: SQLite (`var/data_test.db`)
- **Migrations**: Standard Doctrine migrations in `migrations/`

## Important Notes

- This is a development sandbox, not a production application
- Bundle development should follow PSR-12 coding standards
- All bundles must maintain backward compatibility within major versions
- The monorepo manages version synchronization across all packages
- Each bundle has its own test suite that should be maintained
