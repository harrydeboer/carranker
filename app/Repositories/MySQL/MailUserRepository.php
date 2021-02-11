<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\MailUser;
use App\Repositories\Interfaces\MailUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

class MailUserRepository implements MailUserRepositoryInterface
{
    public function __construct(
        private MailUser $mailUser,
    ) {
    }

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

    public function update(MailUser $mailUser): void
    {
        $mailUser->save();
    }

    public function delete(int $id): void
    {
        MailUser::destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function findAll(int $numMailUsersPerPage): LengthAwarePaginatorContract
    {
        return $this->mailUser->paginate($numMailUsersPerPage);
    }

    public function getByEmail(string $email): MailUser
    {
        return $this->mailUser->where('email', $email)->first();
    }
}
