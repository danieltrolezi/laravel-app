#!/bin/sh

echo "Creating Git Hooks..."
cp ./github/hooks/pre-commit ./.git/hooks/
chmod +x ./.git/hooks/pre-commit

if [ ! -f ./.env ]; then
    echo "Creating .env file..."
    cp ./.env.example /.env
fi