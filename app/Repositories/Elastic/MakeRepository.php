<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Elastic\BaseModel;
use App\Models\Elastic\Make;

class MakeRepository extends BaseRepository
{
    public function __construct(Make $make)
    {
        $this->model = $make;
    }

    public function get(int $id): Make
    {
        return Make::get($id);
    }

    public function getByName(string $name): BaseModel
    {
        $params = [
            'index' => $this->model->getIndex(),
            'size' => 1,
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => $name,
                    ],
                ],
            ],
        ];

        $model = Make::searchOne($params);

        if (is_null($model)) {
            abort(404, "The requested make does not exist.");
        }

        return $model;
    }

    /** The make names are retrieved and sorted on ascii value.
     * This is needed for makes with special characters in their name to be sorted properly.
     */
    public function getMakeNames(): array
    {
        $params = [
            'index' => $this->model->getIndex(),
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];
        $makes = Make::searchMany($params);
        $makenames = [];
        $makesASCII = array();
        foreach($makes as $make) {
            $makenames[$make->getName()] = $make->getName();
            $makesASCII[] = strtolower(preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities($make->getName())));
        }
        array_multisort($makesASCII, $makenames);

        return $makenames;
    }

    /** The model names are retrieved and sorted on ascii value.
     * This is needed for models with special characters in their name to be sorted properly.
     */
    public function getModelNames(?string $makename): ?array
    {
        if (is_null($makename)) {
            return null;
        }

        $models = $this->getByName($makename)->getModels();
        $modelnames = [];
        $modelsASCII = [];
        foreach($models as $model) {
            $modelnames[] = $model->getName();
            $modelsASCII[] = strtolower(preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities($model->getName())));
        }
        array_multisort($modelsASCII, $modelnames);

        return $modelnames;
    }
}
