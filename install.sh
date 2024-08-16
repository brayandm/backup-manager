#!/bin/bash

set -e

source .env

mkdir -p /opt/backup-manager
cd /opt/backup-manager

VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all

curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/main/docker-compose.yml
VERSION=$VERSION docker compose up -d