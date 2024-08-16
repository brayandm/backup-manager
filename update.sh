#!/bin/bash

set -e

curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/uninstall.sh
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/install.sh

chmod +x uninstall.sh
chmod +x install.sh

sudo ./uninstall.sh --keep-volumes

sudo VERSION=XVERSION ./install.sh

rm uninstall.sh
rm install.sh