#!/bin/bash

set -e

if [ -d "/opt/backup-manager" ]; then

  echo "Checking if Docker Compose is running..."
  if ! docker compose ps | grep -q "Up"; then
    echo "Backup Manager is not running."
    exit 0
  fi

  echo "Stopping Backup Manager."
  VERSION=$(cat VERSION)
  VERSION=$VERSION docker compose down --remove-orphans
  echo "Backup Manager has been stopped."
else
  echo "Backup Manager is not installed."
fi
