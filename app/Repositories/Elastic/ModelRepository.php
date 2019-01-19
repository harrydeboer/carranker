<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Elastic\Model;

class ModelRepository extends BaseRepository
{
    public function getByName(string $name): Model
    {
        $model = $this->modelClassName::searchByQuery(['match' =>['name' => $name]])->first();

        if (is_null($model)) {
            abort(404, "The requested model does not exist.");
        }

        return $model;
    }
}