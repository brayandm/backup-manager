#!/bin/bash

VERSION=1.0.0
export VERSION

cd backend/
docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull --build-arg VERSION=$VERSION
docker compose -f docker-compose.build.yml push

cd ../frontend/
docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull --build-arg VERSION=$VERSION
docker compose -f docker-compose.build.yml push
