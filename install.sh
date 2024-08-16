#!/bin/bash

set -e

VERSION=1.0.0

mkdir -p /opt/backup-manager
cd /opt/backup-manager

curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/main/docker-compose.yml
VERSION=$VERSION docker compose up -d