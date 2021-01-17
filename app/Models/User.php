<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * The user has the same table as wordpress. One column is added to the wordpress table: remember_token.
 * @mixin Builder
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->email_verified_at;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    /**
     * The users have multiple roles and the roles have multiple users so these are many to many.
     */
    public function getRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function getRatings(): Collection
    {
        return $this->hasMany(Rating::class,'user_id', 'id')->get();
    }

    public function sendPasswordResetNotification($token)
    {
        if (env('APP_ENV') === 'production') {
            $this->notify(new ResetPasswordNotification($token));
        }
    }

    public function sendEmailVerificationNotification()
    {
        if (env('APP_ENV') === 'production') {
            $this->notify(new VerifyEmail());
        }
    }
}
