# Backup Manager vXVERSION

## Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

## How to install

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/install.sh && chmod +x install.sh && (VERSION=XVERSION sudo ./install.sh || true) && rm install.sh
```

## How to open

```bash
open http://localhost:3001
```

## How to uninstall

```bash
curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/uninstall.sh && chmod +x uninstall.sh && (sudo ./uninstall.sh || true) && rm uninstall.sh
```

## How to update

```bash
curl -o update.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/update.sh && chmod +x update.sh && (sudo ./update.sh || true) && rm update.sh
```
