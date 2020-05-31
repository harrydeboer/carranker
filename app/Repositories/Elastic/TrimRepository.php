<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Aspect;
use App\Models\Elastic\Trim;
use App\Forms\FilterTopForm;
use App\CarSpecs;
use Illuminate\Database\Eloquent\Collection;

class TrimRepository extends BaseRepository
{
    protected string $index = 'trims';

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
    public function findTrimsOfTop(FilterTopForm $form, int $minNumVotes, int $lengthTopTable, int $offset=null): Collection
    {
        $queryObj = $this->queryAspects($form);
        if ($form->hasRequest) {
            foreach (CarSpecs::specsChoice() as $key => $spec) {
                $queryObj = $this->queryChoice($spec['choices'], $key, $queryObj, $form);
            }

            foreach (CarSpecs::specsRange() as $key => $spec) {
                $queryObj = $this->queryRange($spec, $key, $queryObj, $form);
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
    private function queryAspects(FilterTopForm $form): Builder
    {
        $selectAspects = "*, (";
        $total = 0;
        $formAspects = $form->aspects;
        foreach (Aspect::getAspects() as $aspect) {
            $total += $formAspects[$aspect];
            $selectAspects .= "? * " . strtolower($aspect) . " + ";
        }
        $selectAspects = substr($selectAspects, 0, -3);
        $selectAspects .= ") / $total as rating";

        return Trim::selectRaw($selectAspects, $formAspects);
    }

    /** Filter the trims for the user settings in the dropdowns of the filter top form. */
    private function queryChoice(array $choices, string $name, Builder $queryObj, FilterTopForm $form): Builder
    {
        $queryArr = [];
        $formSpec = $form->specsChoice;
        foreach ($choices as $keyItem => $choice) {
            $formVar = $formSpec[$name . $keyItem] ?? false;
            if (isset($formVar) && $formVar === "1") {
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
    private function queryRange(array $spec, string $name, Builder $queryObj, FilterTopForm $form): Builder
    {
        $formSpecs = $form->specsRange;
        $formMin = $formSpecs[$name . 'min'];
        $formMax = $formSpecs[$name . 'max'];

        if (isset($formMin)) {

            /** The database only has year_begin and year_end not generation. Generation is the display name. */
            if ($name === 'generation') {
                $name = 'year_begin';
            }

            $queryObj->where($name, '>=', $formMin);
        }

        /** the max value in the select has to be cast to float or int. */
        if ($name === 'engine_capacity' && !is_null($formMax)) {
            $formMax = (float) $formMax;
        } else if (!is_null($formMax)) {
            $formMax = (int) $formMax;
        }

        if (isset($formMax) && $spec['max'] !== $formMax) {

            /** The database only has year_begin and year_end not generation. Generation is the display name. */
            if ($name === 'generation') {
                $name = 'year_end';
            }

            $queryObj->where($name, '<=', $formMax);
        }

        return $queryObj;
    }
}