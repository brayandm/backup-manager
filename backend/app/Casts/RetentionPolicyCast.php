<?php

namespace App\Casts;

use App\Entities\CompressionMethodConfig;
use App\Entities\Methods\CompressionMethods\NoCompressionMethod;
use App\Entities\Methods\CompressionMethods\TarCompressionMethod;
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
            $retentionPolicy['delete_oldest_backups_when_using_more_megabytes_than']
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
                'delete_oldest_backups_when_using_more_megabytes_than' => $value->getDeleteOldestBackupsWhenUsingMoreMegabytesThan()
            ]
        );
    }
}
