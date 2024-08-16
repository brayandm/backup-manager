# Backup Manager vXVERSION

### Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

### How to install

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
curl -o install.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/install.sh && chmod +x install.sh && (sudo VERSION=XVERSION ./install.sh || true) && rm install.sh
```

### How to open

```bash
open http://localhost:49160
```

### How to uninstall

```bash
curl -o uninstall.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/uninstall.sh && chmod +x uninstall.sh && (sudo ./uninstall.sh || true) && rm uninstall.sh
```

### How to update

```bash
curl -o update.sh https://raw.githubusercontent.com/brayandm/backup-manager/XVERSION/update.sh && chmod +x update.sh && (sudo VERSION=XVERSION ./update.sh || true) && rm update.sh
```

## Features

-   Overview

    -   Metrics about backups and migrations
    -   Amount of backups and migrations per week and year
    -   Free and used space in storage servers

-   Setup data sources
    -   Folder
    -   File
    -   Database
        -   MySQL
        -   PostgreSQL
    -   AWS S3
-   Setup storage servers
    -   AWS S3
    -   Normal server
-   Connections Chainings
    -   SSH
    -   Docker container
-   Backup configuration
    -   Multiple data sources and storage servers
    -   Backup schedule
    -   Backup policy retention
    -   Backup compression
    -   Backup encryption
    -   Backup integrity check
    -   Backup monitoring
-   Migration configuration
    -   Multiple data sources
    -   Migration schedule
    -   Migration compression
    -   Migration monitoring
-   Notifications
    -   Telegram
