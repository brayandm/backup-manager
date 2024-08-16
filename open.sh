#!/bin/bash

set -e

if [ ! -f "~/.local/backup-manager/.active" ]; then
    echo "Backup Manager is not active. Please execute \"backup-manager start\" first."
    exit 0
fi

cd ~/.local/backup-manager

PORT=$(cat .port)

open http://localhost:$PORT