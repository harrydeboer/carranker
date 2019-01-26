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

    public function validate(?string $string): bool
    {
        if (is_null($string)) {
            return true;
        }

        $string = strtolower($string);
        $stringWords = explode(' ', $string);
        foreach ($this->getProfanityNames() as $profanityName) {
            foreach ($stringWords as $word) {
                if ($word === $profanityName) {
                    return false;
                }
            }
        }

        return true;
    }
}
