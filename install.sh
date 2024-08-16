#!/bin/bash

set -e

cleanup() {
  echo "An error occurred. Removing /opt/backup-manager directory."
  rm -rf /opt/backup-manager
}

trap cleanup ERR

is_port_in_use() {
  if lsof -i:$1 > /dev/null; then
    return 0
  else
    return 1
  fi
}

while true; do
  read -p "Please enter the desired port for the application (1024-65535): " PORT

  if ! [[ "$PORT" =~ ^[0-9]+$ ]] || [ "$PORT" -lt 1024 ] || [ "$PORT" -gt 65535 ]; then
    echo "Please enter a valid number between 1024 and 65535."
  elif is_port_in_use $PORT; then
    echo "Port $PORT is already in use. Please choose another port."
  else
    echo "Port $PORT is available."
    break
  fi
done

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
