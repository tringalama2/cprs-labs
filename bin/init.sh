#!/bin/bash

# Local Dev New Machine

command_exists() {
    command -v "$@" > /dev/null 2>&1
}

if ! command_exists composer; then
    echo "composer is not installed"
    echo "Visit https://getcomposer.org/download/ to install"
    exit
fi

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install

echo "Set up your .env file with MySQL credentials, then run 'npm run dev'"
