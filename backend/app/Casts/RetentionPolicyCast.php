<?php

namespace App\Casts;

use App\Entities\RetentionPolicyConfig;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class RetentionPolicyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $retentionPolicy = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        return new RetentionPolicyConfig(
            $retentionPolicy['keep_all_backups_for_days'],
            $retentionPolicy['keep_daily_backups_for_days'],
            $retentionPolicy['keep_weekly_backups_for_weeks'],
            $retentionPolicy['keep_monthly_backups_for_months'],
            $retentionPolicy['keep_yearly_backups_for_years'],
            $retentionPolicy['delete_oldest_backups_when_using_more_megabytes_than'],
            $retentionPolicy['retention_policy_inf_size'] ?? false,
            $retentionPolicy['disable_retention_policy'] ?? false
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {

        return json_encode(
            [
                'keep_all_backups_for_days' => $value->getKeepAllBackupsForDays(),
                'keep_daily_backups_for_days' => $value->getKeepDailyBackupsForDays(),
                'keep_weekly_backups_for_weeks' => $value->getKeepWeeklyBackupsForWeeks(),
                'keep_monthly_backups_for_months' => $value->getKeepMonthlyBackupsForMonths(),
                'keep_yearly_backups_for_years' => $value->getKeepYearlyBackupsForYears(),
                'delete_oldest_backups_when_using_more_megabytes_than' => $value->getDeleteOldestBackupsWhenUsingMoreMegabytesThan(),
                'retention_policy_inf_size' => $value->getRetentionPolicyInfSize(),
                'disable_retention_policy' => $value->getDisableRetentionPolicy(),
            ]
        );
    }
}
