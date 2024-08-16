#!/bin/bash

set -e

XVERSION=1.0.0

cd backend/
XVERSION=$XVERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
XVERSION=$XVERSION docker compose -f docker-compose.build.yml push
XVERSION
cd ../frontend/
XVERSION=$XVERSION docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull 
XVERSION=$XVERSION docker compose -f docker-compose.build.yml push
