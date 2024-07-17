<?php

namespace App\Entities;

class RetentionPolicyConfig
{
    private $keepAllBackupsForDays;
    private $keepDailyBackupsForDays;
    private $keepWeeklyBackupsForWeeks;
    private $keepMonthlyBackupsForMonths;
    private $keepYearlyBackupsForYears;
    private $deleteOldestBackupsWhenUsingMoreMegabytesThan;


    public function __construct(
        $keepAllBackupsForDays,
        $keepDailyBackupsForDays,
        $keepWeeklyBackupsForWeeks,
        $keepMonthlyBackupsForMonths,
        $keepYearlyBackupsForYears,
        $deleteOldestBackupsWhenUsingMoreMegabytesThan)
    {
        $this->keepAllBackupsForDays = $keepAllBackupsForDays;
        $this->keepDailyBackupsForDays = $keepDailyBackupsForDays;
        $this->keepWeeklyBackupsForWeeks = $keepWeeklyBackupsForWeeks;
        $this->keepMonthlyBackupsForMonths = $keepMonthlyBackupsForMonths;
        $this->keepYearlyBackupsForYears = $keepYearlyBackupsForYears;
        $this->deleteOldestBackupsWhenUsingMoreMegabytesThan = $deleteOldestBackupsWhenUsingMoreMegabytesThan;
    }

    public function getKeepAllBackupsForDays(){
        return $this->keepAllBackupsForDays;
    }

    public function getKeepDailyBackupsForDays(){
        return $this->keepDailyBackupsForDays;
    }

    public function getKeepWeeklyBackupsForWeeks(){
        return $this->keepWeeklyBackupsForWeeks;
    }

    public function getKeepMonthlyBackupsForMonths()
    {
        return $this->keepMonthlyBackupsForMonths;

    }

    public function getKeepYearlyBackupsForYears(){
        return $this->keepYearlyBackupsForYears;
    }

    public function getDeleteOldestBackupsWhenUsingMoreMegabytesThan(){
        return $this->deleteOldestBackupsWhenUsingMoreMegabytesThan;
    }
}
