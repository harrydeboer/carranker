<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IUserRepository;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $modelClassName;

    public function getByName(string $username): ?User
    {
        return User::where('user_login', $username)->first();
    }

    public function getByEmail(string $useremail): ?User
    {
        return User::where('user_email', $useremail)->first();
    }

    public function getRatingsTrim(?Authenticatable $user, int $trimId): ?Rating
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')->where('trim_id', $trimId)->first();
    }

    public function getRatingsModel(?Authenticatable $user, int $modelId): ?Collection
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')->where('model_id', $modelId)->get()->keyBy('trim_id');
    }
}
