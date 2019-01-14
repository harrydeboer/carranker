<?php

namespace App\Repositories;

use App\Models\Make;

class MakeRepository extends BaseRepository
{
    public function getMakenames()
    {
        $makes = Make::all();
        $makenames = ['' => 'Make'];
        foreach($makes as $make) {
            $makenames[$make->getName()] = $make->getName();
        }
        return $makenames;
    }

    public function findMakesForSearch(string $searchString)
    {
        $words = explode(' ', $searchString);

        $queryObj = Make::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        $test = $queryObj->get();

        return $test;
    }

    public function getByName(string $name): Make
    {
        $result = Make::where('name', $name)->first();

        if (is_null($result)) {
            abort(404, "The requested make does not exist.");
        }

        return $result;
    }
}
