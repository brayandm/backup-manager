#!/bin/bash

set -e

cd /opt/backup-manager

PORT=$(cat .port)

open http://localhost:$PORT