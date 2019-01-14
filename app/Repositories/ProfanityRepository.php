<?php

namespace App\Repositories;

class ProfanityRepository extends BaseRepository
{
    public function getProfanityNames(): array
    {
        $profanitiesObjects = $this->all();

        $profanities = [];
        foreach ($profanitiesObjects as $profanitiesObject) {
            $profanities[] = $profanitiesObject->getName();
        }

        return $profanities;
    }
}
