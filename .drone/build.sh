#!/bin/bash

echo "Started building at $(date)"

# Update composer
composer self-update

# Install dependencies
composer install

cp jorobo.dist.ini jorobo.ini

vendor/bin/robo build