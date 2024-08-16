# Backup Manager v1.0.0

### Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

### How to install

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Download backup-manager script and add to PATH:

```bash
mkdir -p ~/.local/backup-manager-master/ && cd ~/.local/backup-manager-master/ && curl -o backup-manager https://raw.githubusercontent.com/brayandm/backup-manager/1.0.0/backup-manager.sh && chmod 700 backup-manager && echo "export PATH=\"~/.local/backup-manager-master/:\$PATH\"" >> ~/.bashrc && source ~/.bashrc
```

### How to install

```bash
backup-manager install 1.0.0
```

### How to start

```bash
backup-manager start
```

### How to open

```bash
backup-manager open
```

### How to stop

```bash
backup-manager stop
```

### How to uninstall

```bash
backup-manager uninstall
```

### How to update other version

```bash
backup-manager update x.x.x
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
