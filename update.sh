#!/bin/bash

set -e

if [ -d "/opt/backup-manager" ]; then
  echo "Uninstalling Backup Manager from /opt/backup-manager but keeping the data."
  cd /opt/backup-manager
  OLDVERSION=cat VERSION
  VERSION=$OLDVERSION docker compose down --remove-orphans --rmi all
  rm -rf /opt/backup-manager
  echo "Backup Manager has been uninstalled."
else
  echo "Backup Manager is not installed."
fi

if [ -d "/opt/backup-manager" ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in /opt/backup-manager."
  mkdir -p /opt/backup-manager
  cd /opt/backup-manager
  echo $VERSION > VERSION
  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
  VERSION=$VERSION docker compose up -d
  echo "Backup Manager has been installed."
fi