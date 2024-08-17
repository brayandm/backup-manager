#!/bin/bash

set -e

cleanup() {
  echo "An error occurred. Removing ~/.local/backup-manager directory."
  cd ~/.local/
  rm -rf backup-manager/
}

trap cleanup ERR

if [ -d ~/.local/backup-manager ]; then
  echo "Backup Manager is already installed."
else
  echo "Installing Backup Manager in ~/.local/backup-manager."
  mkdir -p ~/.local/backup-manager
  cd ~/.local/backup-manager

  echo $VERSION > VERSION

  curl --silent -o uninstall https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/uninstall.sh
  curl --silent -o start https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/start.sh
  curl --silent -o stop https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/stop.sh
  curl --silent -o open https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/open.sh

  chmod +x uninstall
  chmod +x start
  chmod +x stop
  chmod +x open

  curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/docker-compose.yml
  VERSION=$VERSION docker compose pull
  echo "Backup Manager has been installed."
fi
