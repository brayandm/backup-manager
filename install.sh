#!/bin/bash

set -e

source .env

if [ -d "/opt/backup-manager" ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in /opt/backup-manager."
  mkdir -p /opt/backup-manager
  cd /opt/backup-manager
  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
  VERSION=$VERSION docker compose up -d
fi