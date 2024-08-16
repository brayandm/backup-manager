#!/bin/bash

set -e

XVERSION=1.0.0

if [ -d "/opt/backup-manager" ]; then
  echo "Uninstalling Backup Manager from /opt/backup-manager."
  cd /opt/backup-manager
  VERSION=$XVERSION docker compose down --volumes --remove-orphans --rmi all
  rm -rf /opt/backup-manager
  echo "Backup Manager has been uninstalled."
else
  echo "Backup Manager is not installed."
fi