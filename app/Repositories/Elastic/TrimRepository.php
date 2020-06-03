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
    public function findTrimsOfTop(FilterTopForm $form, int $minNumVotes, int $lengthTopTable, int $offset=0): Collection
    {
        $params = [
            'index' => $this->index,
            'size' => $lengthTopTable - $offset,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "range" => [
                                    "votes" => [
                                        "gte" => $minNumVotes,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $params = $this->queryAspects($form, $params);
        if ($form->hasRequest) {
            foreach (CarSpecs::specsChoice() as $key => $spec) {
                $params = $this->queryChoice($spec['choices'], $key, $params, $form);
            }

            foreach (CarSpecs::specsRange() as $key => $spec) {
                $params = $this->queryRange($spec, $key, $params, $form);
            }
        }

        if (!$offset !== 0) {
            $params['from'] = $offset;
        }

        $trims = Trim::search($params, null, 'rating');
        foreach ($trims as $key => $trim) {
            $trims[$key]->setRatingFiltering($trim->rating);
        }

        return $trims;
    }

    /** Filter the trims for the user settings in the aspect ranges of the filter top form. */
    private function queryAspects(FilterTopForm $form, array $params): array
    {
        $params['body']['sort']['_script'] = [
            'type' => 'number',
            'script' => [
                'lang' => 'expression',
            ],
            'order' => 'desc',
        ];

        $source = '(';
        $factorArray = [];
        $total = 0;
        $formAspects = $form->aspects;
        foreach (Aspect::getAspects() as $key => $aspect) {
            $source .= "doc['" . $aspect . "'] * factor" . $key . " + ";
            $factorArray['factor' . $key] = (int) $formAspects[$aspect];
            $total += (int) $formAspects[$aspect];
        }
        $params['body']['sort']['_script']['script']['source'] = substr($source, 0, -3) . ")/ $total";
        $params['body']['sort']['_script']['script']['params'] = $factorArray;

        return $params;
    }

    /** Filter the trims for the user settings in the dropdowns of the filter top form. */
    private function queryChoice(array $choices, string $name, array $params, FilterTopForm $form): array
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
                    if (!in_array('Gasoline,  Electric', $queryArr)) {
                        $queryArr[] = 'Gasoline,  Electric';
                    }
                }
                if ($name === 'fuel' && ($choice === 'CNG' || $choice === 'Gasoline')) {
                    if (!in_array('Gasoline,  CNG', $queryArr)) {
                        $queryArr[] = 'Gasoline,  CNG';
                    }
                }
            }
        }

        if ($queryArr === [] || count($queryArr) === count($choices)) {
            return $params;
        }
        $array = [];
        $array['terms'][$name] = $queryArr;

        $params['body']['query']['bool']['must'][] = $array;

        return $params;
    }

    /** Filter the trims for the user settings in the min/max selects of the filter top form. */
    private function queryRange(array $spec, string $name, array $params, FilterTopForm $form): array
    {
        $formSpecs = $form->specsRange;
        $formMin = $formSpecs[$name . 'min'];
        $formMax = $formSpecs[$name . 'max'];

        if (isset($formMin)) {

            /** The database only has year_begin and year_end not generation. Generation is the display name. */
            if ($name === 'generation') {
                $name = 'year_begin';
            }
            $array = [];
            $array['range'][$name] = ['gte' => (double) $formMin];

            $params['body']['query']['bool']['must'][] = $array;
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
            $array = [];
            $array['range'][$name] = ['lte' => (double) $formMax];

            $params['body']['query']['bool']['must'][] = $array;
        }

        return $params;
    }
}