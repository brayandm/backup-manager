#!/bin/bash

set -e

cleanup() {
  echo "An error occurred. Removing /opt/backup-manager directory."
  rm -rf /opt/backup-manager
}

trap cleanup ERR

if [ -d "/opt/backup-manager" ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in /opt/backup-manager."
  mkdir -p /opt/backup-manager
  cd /opt/backup-manager
  echo $VERSION > VERSION
  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
  VERSION=$VERSION docker compose pull
  echo "Backup Manager has been installed."
fi
