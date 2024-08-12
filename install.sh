#!/bin/sh

echo "Creating Git Hooks..."
cp ./git-hooks/pre-commit ./.git/hooks/
chmod +x ./.git/hooks/pre-commit

echo "Running migrations..."
docker exec app php artisan migrate --seed

if [ ! -f ./.env ]; then
    echo "Creating .env file"
    cp ./.env.example /.env
fi

echo "Setting APP Key..."
docker exec app php artisan key:generate --ansi

echo "Creating Database Test..."
docker exec db mysql -u root -psecret -e "create database if not exists laravel_test"

echo "Creating Root User..."
docker exec app php artisan app:create-root