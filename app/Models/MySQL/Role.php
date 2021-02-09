<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends AbstractModel
{
    use HasFactory;

    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['name'];

    /**
     * The roles have multiple users and the users have multiple roles so these are many to many.
     */
    public function getUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_roles');
    }

    public function getName(): string
    {
        return $this->name;
    }
}
