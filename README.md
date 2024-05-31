# Backup Manager

## Setup Git Hooks

```bash
cp .hooks/* .git/hooks/
```

## How to run

1 - [Install Docker Engine](https://docs.docker.com/engine/install/)

2 - Run script:

```bash
curl -o start.sh https://raw.githubusercontent.com/brayandm/backup-manager/main/start.sh && chmod +x start.sh && ./start.sh
```

## Features

-   [ ] Backup files, directories and databases
-   [ ] Backup to local, remote and cloud storage
-   [ ] Backup to multiple storage locations
-   [ ] Manage backup schedules
-   [ ] Manage backup retention policies
-   [ ] Manage backup encryption
-   [ ] Manage backup compression
-   [ ] Manage backup notifications
-   [ ] Manage backup logs
-   [ ] Manage backup reports
-   [ ] Manage backup status
-   [ ] Manage backup health
-   [ ] Manage backup restore
-   [ ] Manage backup monitoring
-   [ ] Manage Telegram notifications

## UI Design

-   [ ] Dashboard (Backup Status, Backup Health, Backup Logs, Backup Reports, Create/Update/Delete Backup)
-   [ ] Admin Page (change password, update profile, telegram notifications)
-   [ ] Backup Specific Page (Several Tabs, Update Backup Configuration, View Backup FIles, View Backup Reports)

## Admin Page

-   [ ] Change Password
-   [ ] Update Profile
-   [ ] Telegram Notifications
-   [ ] Side Bar to navigate

## Dashboard

-   [ ] Left Part: Statistics

## Classes

-   Conections (Move & Push & Pull & Setup & Clean):

    -   SSH
    -   Docker

-   Drivers (Push & Pull & Setup & Clean):
    -   Cloud Storage
    -   Database
    -   File System
