#!/bin/bash

set -e

if [ -d "/opt/backup-manager" ]; then

  cd /opt/backup-manager

  echo "Checking if Backup Manager is active..."
  if [ ! -f "/opt/backup-manager/.active" ]; then
    echo "Backup Manager is not active."
    exit 0
  fi

  echo "Stopping Backup Manager."
  VERSION=$(cat VERSION)
  VERSION=$VERSION docker compose down --remove-orphans
  rm .active
  echo "Backup Manager has been stopped."
else
  echo "Backup Manager is not installed."
fi
