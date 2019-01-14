<?php

declare(strict_types=1);

namespace App\Repositories;

class UserRepository extends BaseRepository
{
    protected $modelClassName;

    public function __construct()
    {
        parent::__construct();
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = '\App\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function getRatings($user, string $modelOrTrim, int $id)
    {
        return $user->getRatings($modelOrTrim, $id);
    }
}