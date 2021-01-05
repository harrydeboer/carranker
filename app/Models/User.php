<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/** The user has the same table as wordpress. One column is added to the wordpress table: remember_token. */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function sendPasswordResetNotification($token)
    {
        if (env('APP_ENV') === 'production') {
            $this->notify(new ResetPasswordNotification($token));
        }
    }

    public function sendEmailVerificationNotification()
    {
        if (env('APP_ENV') === 'production') {
            $this->notify(new VerifyEmail);
        }
    }
}
