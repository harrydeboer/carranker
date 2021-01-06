<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = 'roles';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /** The roles have multiple users and the users have multiple roles so these are many to many. */
    public function getUsers(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'users_roles');
    }

    public function getName(): string
    {
        return $this->name;
    }
}