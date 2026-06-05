#!/usr/bin/env bash
# Exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

# આ લાઇન ડેટાબેઝને લાઇવ સાઇટ પર અપડેટ કરશે
php artisan migrate --force
