<?php

namespace App\Repositories;

use App\Models\Profanity;
use Illuminate\Database\Eloquent\Collection;

class ProfanityRepository implements IRepository
{
    public function all(): Collection
    {
        return Profanity::all();
    }

    public function get(int $id): Profanity
    {
        return Profanity::findOrFail($id);
    }

    public function create(array $createArray): Profanity
    {
        $model = new Profanity($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        Profanity::destroy($id);
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
