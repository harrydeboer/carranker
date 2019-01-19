<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Elastic\Make;

class MakeRepository extends BaseRepository
{
    public function getByName(string $name): Make
    {
        $make = $this->modelClassName::searchByQuery(['match' =>['name' => $name]])->first();

        if (is_null($make)) {
            abort(404, "The requested make does not exist.");
        }

        return $make;
    }
}