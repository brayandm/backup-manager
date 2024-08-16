# Backup Manager

## Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

## How to run

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
mkdir -p /opt/backup-manager
cd /opt/backup-manager
curl -o start.sh https://raw.githubusercontent.com/brayandm/backup-manager/main/start.sh
chmod +x start.sh
./start.sh
```
