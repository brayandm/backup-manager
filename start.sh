#!/bin/bash

curl -o docker-compose.yml https://raw.githubusercontent.com/brayandm/backup-manager/main/docker-compose.yml
docker compose up -d
open http://localhost:3001