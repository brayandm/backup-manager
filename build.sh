#!/bin/bash

set -e

XVERSION=1.0.0

cd backend/
VERSION=$XVERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
VERSION=$XVERSION docker compose -f docker-compose.build.yml push
XVERSION
cd ../frontend/
VERSION=$XVERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull 
VERSION=$XVERSION docker compose -f docker-compose.build.yml push
