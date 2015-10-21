#!/bin/bash

echo "Started building at $(date)"
# Update composer
composer self-update

# Install dependencies
composer install

vendor/bin/robo build