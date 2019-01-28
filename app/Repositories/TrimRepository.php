<?php

namespace App\Repositories;

use App\CarSpecs;
use App\Models\Aspect;
use App\Models\Trim;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TrimRepository extends CarRepository
{
    public function findTrimsForSearch(string $searchString): Collection
    {
        $words = explode(' ', $searchString);

        $queryObj = Trim::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        return $queryObj->get();
    }

    public function findSelectedGeneration(?Trim $trim): ?string
    {
        if (is_null($trim)) {
            return null;
        }

        return $trim->getYearBegin() . '-' . $trim->getYearEnd();
    }

    /** The trims for the top on the homepage are retrieved. The filtering options are used when present.
     * There is an aspectfilter, specs choice filter and specs range filter. The minimum number of votes is also a
     * filter and the number of trims to be retrieved and the offset if present. The ratings are sorted from hight to low.
     */
    public function findTrimsOfTop(SessionManager $session, int $minNumVotes, int $lengthTopTable, int $offset=null): Collection
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
            $trims[$key]->setRatingFiltering($trim->rating);
        }

        return $trims;
    }

    /** Filter the trims for the user settings in the aspect ranges of the filter top form. */
    private function queryAspects(SessionManager $session)
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
    private function queryChoice(array $choices, string $name, Builder $queryObj, SessionManager $session): Builder
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

                /** The fuel can be both Gasoline and Electric or Gasoline and CNG per trim. */
                if ($name === 'fuel' && ($choice === 'Electric' || $choice === 'Gasoline')) {
                    $queryArr[] = 'Gasoline,  Electric';
                }
                if ($name === 'fuel' && ($choice === 'CNG' || $choice === 'Gasoline')) {
                    $queryArr[] = 'Gasoline,  CNG';
                }
            }
        }

        if ($queryArr === [] || count($queryArr) === count($choices)) {
            return $queryObj;
        }

        return $queryObj->whereIn($name, $queryArr);
    }

    /** Filter the trims for the user settings in the min/max selects of the filter top form. */
    private function queryRange(array $spec, string $name, Builder $queryObj, SessionManager $session): Builder
    {
        $sessionSpecs = $session->get('specsRange');
        $sessionMin = $sessionSpecs[$name . 'min'];
        $sessionMax = $sessionSpecs[$name . 'max'];

        if (isset($sessionMin)) {

            /** The database only has year_begin and year_end not generation. Generation is the display name. */
            if ($name === 'generation') {
                $name = 'year_begin';
            }

            $queryObj->where($name, '>=', $sessionMin);
        }

        /** the max value in the select has to be cast to float or int. */
        if ($name === 'engine_capacity' && !is_null($sessionMax)) {
            $sessionMax = (float) $sessionMax;
        } else if (!is_null($sessionMax)) {
            $sessionMax = (int) $sessionMax;
        }

        if (isset($sessionMax) && $spec['max'] !== $sessionMax) {

            /** The database only has year_begin and year_end not generation. Generation is the display name. */
            if ($name === 'generation') {
                $name = 'year_end';
            }

            $queryObj->where($name, '<=', $sessionMax);
        }

        return $queryObj;
    }
}
