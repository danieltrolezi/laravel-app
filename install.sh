#!/bin/sh

echo "Creating Git Hooks..."
cp ./git-hooks/pre-commit ./.git/hooks/
chmod +x ./.git/hooks/pre-commit

echo "Running migrations..."
docker exec app php artisan migrate --seed

echo "Setting APP Key"
docker exec app php artisan key:generate --ansi