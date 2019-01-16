<?php

namespace App\Repositories;

use App\CarSpecs;
use App\Models\Aspect;
use App\Models\Trim;
use Illuminate\Support\Facades\DB;

class TrimRepository extends BaseRepository
{
    use CarTrait;

    public function findTrimsForSearch(string $searchString)
    {
        $words = explode(' ', $searchString);

        $queryObj = Trim::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        return $queryObj->get();
    }

    public function findTrimsOfTop($session, int $minNumVotes, int $lengthTopTable, int $offset=null)
    {
        $queryObj = $this->queryAspects($session);

        $sessionAspects = $session->get('aspects');
        if (isset($sessionAspects)) {
            foreach (CarSpecs::specsChoice() as $key => $spec) {
                $queryObj = $this->queryChoice($spec['choices'], $key, $queryObj, $session);
            }

            foreach (CarSpecs::specsRange() as $key => $spec) {
                $queryObj = $this->queryRange($spec, $key, $queryObj, $session);
            }
        }

        $queryObj->where('votes', '>', $minNumVotes)->take($lengthTopTable)->orderBy('rating', 'desc');
        if (!is_null($offset)) {
            $queryObj->offset($offset)->limit($lengthTopTable - $offset);
        }
        $trims = $queryObj->get();
        foreach ($trims as $key => $trim) {
            $trims[$key]->setRating($trim->rating);
        }

        return $trims;
    }

    /** Filter the trims for the user settings in the aspect ranges of the filter top form. */
    private function queryAspects($session)
    {
        $selectAspects = "(";
        $total = 0;
        $sessionAspects = $session->get('aspects');
        foreach (Aspect::getAspects() as $aspect) {
            if ($sessionAspects[$aspect] !== null) {
                $total += $sessionAspects[$aspect];
                $selectAspects .= $sessionAspects[$aspect] . " * " . strtolower($aspect) . " + ";
            } else {
                $total += 1;
                $selectAspects .= strtolower($aspect) . " + ";
            }
        }
        $selectAspects = substr($selectAspects, 0, -3);
        $selectAspects .= ") / $total as rating";

        return Trim::select('trims.*', DB::raw($selectAspects));
    }

    /** Filter the trims for the user settings in the dropdowns of the filter top form. */
    private function queryChoice($choices, string $name, $queryObj, $session)
    {
        $queryArr = [];
        $sessionSpec = $session->get('specsChoice');
        foreach ($choices as $keyItem => $choice) {
            $sessionVar = $sessionSpec[$name . $keyItem] ?? false;
            if (isset($sessionVar) && $sessionVar === "1") {
                $queryArr[] = $choice;

                /** The gearbox type can be both manual and automatic per trim. */
                if ($name === 'gearbox_type' && $choice === 'Manual') {
                    $queryArr[] = 'Manual/Automatic';
                }
                if ($name === 'gearbox_type' && $choice === 'Automatic') {
                    $queryArr[] = 'Manual/Automatic';
                }
            }
        }

        if (empty($queryArr) || count($queryArr) === count($choices)) {
            return $queryObj;
        }

        return $queryObj->whereIn($name, $queryArr);
    }

    /** Filter the trims for the user settings in the min/max selects of the filter top form. */
    private function queryRange($spec, string $name, $queryObj, $session)
    {
        $sessionSpecs = $session->get('specsRange');
        $sessionMin = $sessionSpecs[$name . 'min'];
        $sessionMax = $sessionSpecs[$name . 'max'];

        /** The database only has year_begin and year_end not generation. Generation is the display name. */
        if (isset($sessionMin)) {
            if ($name === 'generation') {
                $name = 'year_begin';
            }

            $queryObj->where($name, '>=', $sessionMin);
        }

        if (isset($sessionMax) && $spec['max'] != $sessionMax) {
            if ($name === 'generation') {
                $name = 'year_end';
            }

            /** When option value Maximum (with the < symbol) is chosen then no filtering. */
            if ($sessionMax !== 'Maximum') {
                $queryObj->where($name, '<=', $sessionMax);
            }
        }

        return $queryObj;
    }
}
