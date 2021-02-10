<?php

declare(strict_types=1);

namespace App\Repositories\Elasticsearch;

use App\Models\Elasticsearch\Make;
use App\Repositories\Interfaces\MakeReadRepositoryInterface;
use stdClass;

class MakeRepository extends AbstractRepository implements MakeReadRepositoryInterface
{
    public function __construct(
        protected Make $model,
    ) {
    }

    public function get(int $id): Make
    {
        return Make::get($id);
    }

    public function getByName(string $name): Make
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

    /**
     * The make names are retrieved and sorted on ascii value.
     * This is needed for makes with special characters in their name to be sorted properly.
     */
    public function getMakeNames(): array
    {
        $params = [
            'index' => $this->model->getIndex(),
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match_all' => new stdClass(),
                ],
            ],
        ];
        $makes = Make::searchMany($params);
        $makeNames = [];
        $makesASCII = array();
        foreach($makes as $make) {
            $makeNames[$make->getName()] = $make->getName();
            $makesASCII[] = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($make->getName())));
        }
        array_multisort($makesASCII, $makeNames);

        return $makeNames;
    }

    /**
     * The model names are retrieved and sorted on ascii value.
     * This is needed for models with special characters in their name to be sorted properly.
     */
    public function getModelNames(?string $makeName): ?array
    {
        if (is_null($makeName)) {
            return null;
        }

        $models = $this->getByName($makeName)->getModels();
        $modelNames = [];
        $modelsASCII = [];
        foreach($models as $model) {
            $modelNames[] = $model->getName();
            $modelsASCII[] = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($model->getName())));
        }
        array_multisort($modelsASCII, $modelNames);

        return $modelNames;
    }
}
