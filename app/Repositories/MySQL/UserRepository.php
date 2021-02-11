<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\User;
use App\Models\MySQL\Rating;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private User $user,
    ) {
    }

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

    public function update(User $user): void
    {
        $user->save();
    }

    public function delete(int $id): void
    {
        User::destroy($id);
    }

    public function getByName(string $username): ?User
    {
        return $this->user->where('name', $username)->first();
    }

    public function getByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function getRatingsTrim(?User $user, int $trimId): ?Rating
    {
        if (is_null($user)) {
            return null;
        }

        return (new Rating())
            ->where('user_id', $user->getId())
            ->where('trim_id', $trimId)
            ->orderBy('time', 'desc')
            ->first();
    }

    public function getRatingsModel(?User $user, int $modelId): ?Collection
    {
        if (is_null($user)) {
            return null;
        }

        return (new Rating())
            ->where('user_id', $user->getId())
            ->where('model_id', $modelId)
            ->get()
            ->keyBy('trim_id');
    }
}
