<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

class MakeRepository extends BaseRepository
{
    protected string $index = 'makes';

    /** The make names are retrieved and sorted on ascii value.
     * This is needed for makes with special characters in their name to be sorted properly.
     */
    public function getMakeNames(): array
    {
        $params = [
            'index' => $this->index,
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];
        $makes = $this->arrayToModels($this->client->search($params));
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

        $models = $this->getByName($makename)[0]->getModels();
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