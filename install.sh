#!/bin/bash

set -e

cleanup() {
  echo "An error occurred. Removing ~/.local/backup-manager directory."
  rm -rf ~/.local/backup-manager
}

trap cleanup ERR

if [ -d ~/.local/backup-manager ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in ~/.local/backup-manager."
  mkdir -p ~/.local/backup-manager
  cd ~/.local/backup-manager
  echo $VERSION > VERSION
  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
  VERSION=$VERSION docker compose pull
  echo "Backup Manager has been installed."
fi
