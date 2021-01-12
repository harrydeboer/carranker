<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MailUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MailUserRepository implements IRepository
{
    public function __construct(
        private MailUser $mailUser,
    ){}

    public function all(): Collection
    {
        return MailUser::all();
    }

    public function get(int $id): MailUser
    {
        return $this->mailUser->findOrFail($id);
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
        return $this->mailUser->paginate($numMailUsersPerPage);
    }

    public function getByEmail(string $email): MailUser
    {
        return $this->mailUser->where('email', $email)->first();
    }
}
