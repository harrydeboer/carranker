<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class TrimService
{
    /** For the frontend it is handy to have all the generations, series and trims of the trims in one array. */
    public function getGenerationsSeriesTrims(Collection $trims): array
    {
        $generationsSeriesTrims = [];
        foreach ($trims as $trim) {

            /** If the trim has a name it means the trim has a specific trim version. */
            if (!is_null($trim->getName())) {
                $generationsSeriesTrims[$trim->getYearBegin() . '-' .
                $trim->getYearEnd()][$trim->getFramework()][$trim->getName()] = $trim->getId();
            } else {
                $generationsSeriesTrims[$trim->getYearBegin() . '-' .
                $trim->getYearEnd()][$trim->getFramework()][] = $trim->getId();
            }
        }
        krsort($generationsSeriesTrims);

        return $generationsSeriesTrims;
    }
}
