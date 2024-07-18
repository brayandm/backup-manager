## Features

-   [ ] Fix retention policy keeping recent backups and make infinite size limit button
-   [ ] Adding SoftDeletes to all models, and thinking about backups deletion logic
-   [ ] Backup databases, aws s3
-   [ ] Backup to cloud storage
-   [ ] Data migration and replication
-   [ ] Manage backup schedules
-   [ ] Manage backup retention policies
-   [ ] Manage backup notifications
-   [ ] Manage backup logs
-   [ ] Manage backup reports
-   [ ] Manage backup status
-   [ ] Manage backup health
-   [ ] Manage backup monitoring
-   [ ] Manage Telegram notifications

## Admin Page

-   [ ] Telegram Notifications

## Dashboard

-   [ ] Menu:

    -   Overview
    -   Data Sources
    -   Storage Servers
    -   Backups Configuration
    -   Migrations Configuration
    -   Reports
    -   Notifications

## Schedule Policy

-   Day of the week
-   Month
-   Day of the month
-   Hour
-   Minute

-   [ ] Manual Backups

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
