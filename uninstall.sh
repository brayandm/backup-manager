#!/bin/bash

set -e

source .env

cd /opt/backup-manager

VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all

rm -rf /opt/backup-manager