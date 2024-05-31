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

-   [ ] Menu:

    -   Overview
    -   Backups
    -   Storage Servers
    -   Reports

## Classes

-   Conections (Run & Push & Pull & Setup & Clean):

    -   SSH
    -   Docker

-   Drivers (Push & Pull & Setup & Clean):

    -   Cloud Storage
    -   Database
    -   File System

-   Command Builder:
    -   Conector-Conector-...-Conector-Driver
    -   Push SPR[SPR[SPC]C]C
    -   Pull SR[SR[SPC]PC]PC
    -   Execute SR[SR[SEC]C]C

## Models

-   Storage Server (Configuration, Metadata, Status)
-   Backup (Configuration, Schedule, Retention Policy, Encryption, Compression, Status, Health)
-   Report
