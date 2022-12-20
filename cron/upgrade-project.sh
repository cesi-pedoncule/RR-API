#!/bin/bash

# This script is used to upgrade the project to the latest version of the API.
# It is run by the cron job on the server.


# Install the latest version of the API
echo "Updating the project..."
git pull origin main

# Run the docker compose file
echo "Building the docker containers..."
docker-compose up -d --build

# Install the dependencies
echo "Installing the dependencies..."
docker exec www_rr_api composer install

# Create the database
echo "Creating the database..."
docker exec www_rr_api php bin/console doctrine:database:create --if-not-exists

# Remove the old migrations
echo "Removing the old migrations..."
docker exec www_rr_api rm -rf migrations/Version*

# Make the migrations
echo "Making the migrations..."
docker exec www_rr_api php bin/console make:migration

# Migrate the database
echo "Migrating the database..."
docker exec www_rr_api php bin/console doctrine:migration:migrate --no-interaction

# Load the fixtures
echo "Loading the fixtures..."
docker exec www_rr_api php bin/console doctrine:fixtures:load --no-interaction

# Clear the cache
echo "Clearing the cache..."
docker exec www_rr_api php bin/console cache:clear