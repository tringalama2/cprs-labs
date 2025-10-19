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

echo "Set up your .env file with your database credentials."

# Pause and wait for any key press
read -n 1 -s -r -p "Press any key to continue once complete..."

echo "" # Add a newline for better formatting after the prompt

php artisan migrate
npm install

echo "Set up your .env file with MySQL credentials, then run 'npm run dev'"
