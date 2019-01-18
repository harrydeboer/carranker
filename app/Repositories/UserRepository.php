<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

class UserRepository extends BaseRepository
{
    protected $modelClassName;

    public function __construct()
    {
        parent::__construct();
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = '\App\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function getRatingsTrim(Authenticatable $user, int $id)
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')->where('trim_id', $id)->first();
    }

    public function getRatingsModel(?Authenticatable $user, int $id)
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')->where('model_id', $id)->get()->keyBy('trim_id');
    }
}