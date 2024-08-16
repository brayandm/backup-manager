#!/bin/bash

set -e

KEEP_VOLUMES=false

while [[ "$#" -gt 0 ]]; do
    case $1 in
        --keep-volumes) KEEP_VOLUMES=true ;;
        *) echo "Unknown option: $1"; exit 1 ;;
    esac
    shift
done

if [ -d "/opt/backup-manager" ]; then
  echo "Uninstalling Backup Manager from /opt/backup-manager."
  cd /opt/backup-manager
  VERSION=$(cat VERSION) || true

  if [ "$KEEP_VOLUMES" = true ]; then
    echo "Keeping volumes."
    VERSION=$VERSION docker compose down --remove-orphans --rmi all || true
  else
    VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all || true
  fi

  rm -rf /opt/backup-manager
  echo "Backup Manager has been uninstalled."
else
  echo "Backup Manager is not installed."
fi
