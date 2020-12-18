<?php

namespace App\Repositories;

use App\Models\Profanity;
use Illuminate\Database\Eloquent\Collection;

class ProfanityRepository extends BaseRepository
{
    private Profanity $profanity;

    public function __construct(Profanity $profanity)
    {
        parent::__construct();
        $this->profanity = $profanity;
    }

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
