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

-   Connections (Run & Push & Pull & Setup & Clean):

    -   SSH
    -   Docker

-   Drivers For Backup (Push & Pull & Setup & Clean, also manage Encryption, Compresion):

    -   Cloud Storage
    -   Database
    -   File System

-   Drivers For Storage Server (Push & Pull & Setup & Clean):

    -   Cloud Storage
    -   File System

-   Command Builder:

    -   Conector-Conector-...-Conector-Driver
    -   Push SPR[SPR[SPC]C]C
    -   Pull SR[SR[SPC]PC]PC
    -   Execute SR[SR[SEC]C]C

-   Layers:
    -   Renaming
    -   Compression
    -   Retention

## Models

-   Storage Server (Configuration, Metadata, Status)
-   Backup (Configuration, Schedule, Retention Policy, Encryption, Compression, Status, Health)
-   Report

## Schedule Policy

-   Day of the week
-   Month
-   Day of the month
-   Hour
-   Minute

## Retention Policy (For daily/weekly/monthly/yearly backups just take the first backup of the day/week/month/year)

keep_all_backups_for_days: 7

-   The number of days for which backups must be kept.

keep_daily_backups_for_days: 16

-   After the "keep_all_backups_for_days" period is over, the most recent backup
    of that day will be kept. Older backups within the same day will be removed.
    If you create backups only once a day, no backups will be removed yet.

keep_weekly_backups_for_weeks: 8

-   After the "keep_daily_backups_for_days" period is over, the most recent backup
    of that week will be kept. Older backups within the same week will be removed.
    If you create backups only once a week, no backups will be removed yet.

keep_monthly_backups_for_months => 4

-   After the "keep_weekly_backups_for_weeks" period is over, the most recent backup
    of that month will be kept. Older backups within the same month will be removed.

keep_yearly_backups_for_years: 2

-   After the "keep_monthly_backups_for_months" period is over, the most recent backup
    of that year will be kept. Older backups within the same year will be removed.

delete_oldest_backups_when_using_more_megabytes_than: 5000

-   After cleaning up the backups remove the oldest backup until
    this amount of megabytes has been reached.
    Set null for unlimited size.
