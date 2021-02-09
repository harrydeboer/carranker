<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Profanity;
use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProfanityRepository implements ProfanityRepositoryInterface
{
    public function __construct(
        private Profanity $profanity,
    ) {
    }

    public function all(): Collection
    {
        return Profanity::all();
    }

    public function get(int $id): Profanity
    {
        return $this->profanity->findOrFail($id);
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
}
