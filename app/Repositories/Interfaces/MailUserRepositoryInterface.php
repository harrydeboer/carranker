<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\MailUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface MailUserRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): MailUser;

    public function create(array $createArray): MailUser;

    public function delete(int $id): void;

    /**
     * @return LengthAwarePaginator
     */
    public function findAll(int $numMailUsersPerPage): LengthAwarePaginatorContract;

    public function getByEmail(string $email): MailUser;
}
