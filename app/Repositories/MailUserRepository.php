<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MailUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MailUserRepository implements IRepository
{
    public function all(): Collection
    {
        return MailUser::all();
    }

    public function get(int $id): MailUser
    {
        return MailUser::findOrFail($id);
    }

    public function create(array $createArray): MailUser
    {
        $model = new MailUser($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        MailUser::destroy($id);
    }

    public function findAll(int $numMailUsersPerPage): LengthAwarePaginator
    {
        return MailUser::paginate($numMailUsersPerPage);
    }

    public function getByEmail(string $email): MailUser
    {
        return MailUser::where('email', $email)->first();
    }
}
