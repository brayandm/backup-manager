# Backup Manager v1.0.0

### Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

### How to install

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/install.sh && chmod +x install.sh && (sudo VERSION=1.0.0 ./install.sh || true) && rm install.sh
```

### How to start

```bash
curl -o appup.sh https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/appup.sh && chmod +x appup.sh && (sudo ./appup.sh || true) && rm appup.sh
```

### How to stop

```bash
curl -o appdown.sh https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/appdown.sh && chmod +x appdown.sh && (sudo ./appdown.sh || true) && rm appdown.sh
```

### How to uninstall

```bash
curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/uninstall.sh && chmod +x uninstall.sh && (sudo ./uninstall.sh || true) && rm uninstall.sh
```

### How to update

```bash
curl -o update.sh https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/update.sh && chmod +x update.sh && (sudo VERSION=1.0.0 ./update.sh || true) && rm update.sh
```

## Screenshots

### Overview

![App Overview](images/app-overview.png)

### Backup Configuration

![Backup Configuration](images/app-backup-configuration.png)

## Features

-   Overview:

    -   [x] Metrics about backups and migrations
    -   [x] Amount of backups and migrations per week and year
    -   [x] Free and used space in storage servers

-   Setup data sources:
    -   [x] Folder
    -   [x] File
    -   Database:
        -   [x] MySQL
        -   [x] PostgreSQL
    -   [x] AWS S3
-   Setup storage servers:
    -   [x] AWS S3
    -   [x] Normal server
-   Connections Chainings:
    -   [x] SSH
    -   [x] Docker container
-   Backup configuration:
    -   [x] Multiple data sources and storage servers
    -   [x] Backup schedule
    -   [x] Backup policy retention
    -   [x] Backup compression
    -   [x] Backup encryption
    -   [x] Backup integrity check
    -   [x] Backup monitoring
-   Migration configuration:
    -   [x] Multiple data sources
    -   [x] Migration schedule
    -   [x] Migration compression
    -   [x] Migration monitoring
-   Notifications:
    -   [x] Telegram
-   Authentication:
    -   [x] JWT
    -   [x] One admin user
