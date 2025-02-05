# EasyCPRSLabs.com

## Requirements

- PHP 8.3, MySQL 8.0 and Laravel 11
- Livewire 3
- Pest for testing
- Authentication is handled by Laravel Breeze
- Tailwind CSS is used for CSS

### PHP Dependencies

- laravel-frontend-presets/tall
- livewire/livewire
- laravel/breeze

### PHP Dev Dependencies

- laravel/pint

### JS Dependencies

- vite
- alpinejs
- vuejs
- luxon

## Installation

### New Local Development

First, clone the repo:

#### `git clone git@github.com:tringalama2/cprs-labs.git .`

Next, run the init script:

#### `./bin/init.sh`

Start a new environment with composer, your env, app key, migrations, and npm.

### Updating the app after merging source

#### `./bin/update.sh`

Install composer, npm, and run migrations.

#### `./bin/precomit.sh`

### Production deployment

#### `./bin/deploy-production.sh`

This script is only for deployment on the production server.
After ensuring that we are in the correct directory on the server that corresponds with our
app `/home/tringalama/admin.fresnoim.com` we perform the following steps:

1. Set Maintenance Mode
2. Update Source Code from Git, reset to head
3. Update PHP dependencies
4. Run Database Migrations
5. Clear caches, routes, configs, views
6. Install node modules
7. Build assets using vite
8. End Maintenance Mode
