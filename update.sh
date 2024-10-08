#!/bin/bash

set -e

cleanup() {
    rm -f uninstall.sh install.sh
}

trap cleanup EXIT

curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/uninstall.sh
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/$VERSION/install.sh

chmod +x uninstall.sh
chmod +x install.sh

./uninstall.sh --keep-volumes
VERSION=$VERSION ./install.sh