<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRepository implements IRepository
{
    public function __construct(
        private User $user,
    ){}

    public function all(): Collection
    {
        return User::all();
    }

    public function get(int $id): User
    {
        return $this->user->findOrFail($id);
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
        return $this->user->where('name', $username)->first();
    }

    public function getByEmail(string $useremail): ?User
    {
        return $this->user->where('email', $useremail)->first();
    }

    public function getRatingsTrim(?User $user, int $trimId): ?Rating
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')
            ->where('trim_id', $trimId)
            ->orderBy('time', 'desc')
            ->first();
    }

    public function getRatingsModel(?User $user, int $modelId): ?Collection
    {
        if (is_null($user)) {
            return null;
        }

        return $user->hasMany('\App\Models\Rating')->where('model_id', $modelId)->get()->keyBy('trim_id');
    }
}
