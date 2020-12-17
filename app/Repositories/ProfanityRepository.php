<?php

namespace App\Repositories;

use App\Interfaces\IProfanityRepository;

class ProfanityRepository extends BaseRepository implements IProfanityRepository
{
    public function getProfanityNames(): string
    {
        $profanitiesObjects = $this->all();

        $profanities = "";
        foreach ($profanitiesObjects as $profanitiesObject) {
            $profanities .= $profanitiesObject->getName() . ' ';
        }
        $profanities = rtrim($profanities, ' ');

        return $profanities;
    }

    public function validate(?string $string): bool
    {
        if (is_null($string)) {
            return true;
        }

        $string = strtolower($string);
        $stringWords = explode(' ', $string);
        foreach ($this->all() as $profanity) {
            foreach ($stringWords as $word) {
                if ($word === $profanity->getName()) {
                    return false;
                }
            }
        }

        return true;
    }
}
