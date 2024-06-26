#!/bin/bash

cd backend/
docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
docker compose -f docker-compose.build.yml push

cd ../frontend/
docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
docker compose -f docker-compose.build.yml push
