#!/bin/bash

VERSION=1.0.0

cd /opt/backup-manager

VERSION=$VERSION docker compose down --volumes --remove-orphans --rmi all

rm -rf /opt/backup-manager