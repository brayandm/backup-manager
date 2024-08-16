#!/bin/bash

set -e

source .env

mkdir -p /opt/backup-manager
cd /opt/backup-manager

curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
VERSION=$VERSION docker compose up -d