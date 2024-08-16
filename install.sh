#!/bin/bash

set -e

XVERSION=1.0.0

if [ -d "/opt/backup-manager" ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in /opt/backup-manager."
  mkdir -p /opt/backup-manager
  cd /opt/backup-manager
  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$XVERSION/docker-compose.yml
  XVERSION=$XVERSION docker compose up -d
  echo "Backup Manager has been installed."
fi