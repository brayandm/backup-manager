#!/bin/bash

VERSION=1.0.0

cd backend/
VERSION=$VERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
docker compose -f docker-compose.build.yml push

cd ../frontend/
VERSION=$VERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull 
docker compose -f docker-compose.build.yml push
