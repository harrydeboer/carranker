<?php

declare(strict_types=1);

namespace App\Repositories\Elasticsearch;

use App\Models\MySQL\Aspects;
use App\Models\Elasticsearch\Trim;
use App\Parameters\CarSpecs;

class TrimRepository extends BaseRepository
{
    public function __construct(
        protected Trim $model,
    ) {
    }

    public function get(int $id): Trim
    {
        return Trim::get($id);
    }

    public function findSelectedGeneration(int $id): ?string
    {
        if ($id === 0) {
            return null;
        }

        $trim = $this->get((int) $id);

        return $trim->getYearBegin() . '-' . $trim->getYearEnd();
    }

    /**
     * The trims for the top on the homepage are retrieved. The filtering options are used when present.
     * There is an aspect filter, specs choice filter and specs range filter. The minimum number of votes is also a
     * filter and the number of trims to be retrieved and the offset if present.
     * The ratings are sorted from high to low.
     */
    public function findTrimsOfTop(array $data, int $minNumVotes, int $lengthTopTable, int $offset=0): array
    {
        $params = [
            'index' => $this->model->getIndex(),
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

        $params = $this->queryAspects($data, $params);
        if ($data !== []) {
            foreach (CarSpecs::specsChoice() as $key => $spec) {
                $params = $this->queryChoice($spec['choices'], $key, $params, $data);
            }

            foreach (CarSpecs::specsRange() as $key => $spec) {
                $params = $this->queryRange($spec, $key, $params, $data);
            }
        }

        if (!$offset !== 0) {
            $params['from'] = $offset;
        }

        $trims = Trim::searchMany($params, 'rating');
        foreach ($trims as $key => $trim) {
            $trims[$key]->setRatingFiltering($trim->getRatingFiltering());
        }

        return $trims;
    }

    /**
     * Filter the trims for the user settings in the aspect ranges of the filter top form.
     */
    private function queryAspects(array $data, array $params): array
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
        foreach (Aspects::getAspects() as $key => $aspect) {
            $source .= "doc['" . $aspect . "'] * factor" . $key . " + ";
            if ($data === []) {
                $factorArray['factor' . $key] = 1;
                $total ++;
            } else {
                $factorArray['factor' . $key] = (int) $data['aspects'][$aspect];
                $total += (int) $data['aspects'][$aspect];
            }
        }
        $params['body']['sort']['_script']['script']['source'] = substr($source, 0, -3) . ")/ $total";
        $params['body']['sort']['_script']['script']['params'] = $factorArray;

        return $params;
    }

    /**
     * Filter the trims for the user settings in the dropdowns of the filter top form.
     */
    private function queryChoice(array $choices, string $name, array $params, array $data): array
    {
        $queryArr = [];
        $formSpec = $data['specs-choice'];
        foreach ($choices as $keyItem => $choice) {
            $formVar = $formSpec[$name . $keyItem] ?? false;
            if (isset($formVar) && $formVar === "on") {
                $queryArr[] = $choice;

                /**
                 * The gearbox type can be both manual and automatic per trim.
                 */
                if ($name === 'gearbox_type' && $choice === 'Manual') {
                    $queryArr[] = 'Manual/Automatic';
                }
                if ($name === 'gearbox_type' && $choice === 'Automatic') {
                    $queryArr[] = 'Manual/Automatic';
                }

                /**
                 * The fuel can be both Gasoline and Electric or Gasoline and CNG per trim.
                 */
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

    /**
     * Filter the trims for the user settings in the min/max selects of the filter top form.
     */
    private function queryRange(array $spec, string $name, array $params, array $data): array
    {
        $formSpecs = $data['specs-range'];
        $formMin = $formSpecs[$name . 'Min'];
        $formMax = $formSpecs[$name . 'Max'];

        if (isset($formMin)) {

            /**
             * The database only has year_begin and year_end not generation. Generation is the display name.
             */
            if ($name === 'generation') {
                $name = 'year_begin';
            }
            $array = [];
            $array['range'][$name] = ['gte' => (double) $formMin];

            $params['body']['query']['bool']['must'][] = $array;
        }

        /**
         * The max value in the select has to be cast to float or int.
         */
        if ($name === 'engine_capacity' && !is_null($formMax)) {
            $formMax = (float) $formMax;
        } elseif (!is_null($formMax)) {
            $formMax = (int) $formMax;
        }

        if (isset($formMax) && $spec['max'] !== $formMax) {

            /**
             * The database only has year_begin and year_end not generation. Generation is the display name.
             */
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
