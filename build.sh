#!/bin/bash

set -e

VERSION=$(cat VERSION)

cd backend/
VERSION=$VERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
VERSION=$VERSION docker compose -f docker-compose.build.yml push

cd ../frontend/
VERSION=$VERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull 
VERSION=$VERSION docker compose -f docker-compose.build.yml push
