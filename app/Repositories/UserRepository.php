<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements IRepository
{
    public function all(): Collection
    {
        return User::all();
    }

    public function get(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $createArray): User
    {
        $model = new User($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        User::destroy($id);
    }

    public function getByName(string $username): ?User
    {
        return User::where('name', $username)->first();
    }

    public function getByEmail(string $useremail): ?User
    {
        return User::where('email', $useremail)->first();
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
