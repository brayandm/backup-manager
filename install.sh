#!/bin/bash

set -e

source .env

mkdir -p /opt/backup-manager
cd /opt/backup-manager

if [ -f docker-compose.yml ]; then
    VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all
fi


curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
VERSION=$VERSION docker compose up -d