# Backup Manager

## Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

## How to install

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/main/install.sh && chmod +x install.sh && ./install.sh
```

## How to open

```bash
open http://localhost:3001
```

## How to uninstall

```bash
curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/main/uninstall.sh && chmod +x uninstall.sh && ./uninstall.sh
```
