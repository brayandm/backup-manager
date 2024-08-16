#!/bin/bash

set -e

cd ~/.local/backup-manager

PORT=$(cat .port)

open http://localhost:$PORT