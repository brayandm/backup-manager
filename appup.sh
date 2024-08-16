#!/bin/bash

set -e

if [ -d "/opt/backup-manager" ]; then

  cd /opt/backup-manager

  echo "Checking if Backup Manager is active..."
  if [ -f "/opt/backup-manager/.active" ]; then
    echo "Backup Manager is already active."
    exit 0
  fi

  DEFAULT_PORT=49160

  is_port_in_use() {
    if ss -tuln | grep -q ":$1 "; then
      return 0
    else
      return 1
    fi
  }

  while true; do
    read -p "Please enter the desired port for the application (1024-65535) [default: $DEFAULT_PORT]: " PORT

    PORT=${PORT:-$DEFAULT_PORT}

    if ! [[ "$PORT" =~ ^[0-9]+$ ]] || [ "$PORT" -lt 1024 ] || [ "$PORT" -gt 65535 ]; then
      echo "Please enter a valid number between 1024 and 65535."
    elif is_port_in_use $PORT; then
      echo "Port $PORT is already in use. Please choose another port."
    else
      echo "Port $PORT is available."
      break
    fi
  done

  echo "Starting Backup Manager."
  VERSION=$(cat VERSION)
  APP_PORT=$PORT VERSION=$VERSION docker compose up -d
  touch .active
  echo $PORT > .port
  echo "Backup Manager is running in http://localhost:$PORT."
else
  echo "Backup Manager is not installed."
fi
