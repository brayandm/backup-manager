#!/bin/bash

set -e

if [ -d "/opt/backup-manager" ]; then
  echo "Uninstalling Backup Manager from /opt/backup-manager."
  cd /opt/backup-manager
  VERSION=$(cat VERSION)
  VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all
  rm -rf /opt/backup-manager
  echo "Backup Manager has been uninstalled."
else
  echo "Backup Manager is not installed."
fi