<?php

declare(strict_types=1);

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/** The user has the same table as wordpress. One column is added to the wordpress table: remember_token. */
class User extends Authenticatable
{
    protected $primaryKey = 'ID';
    protected $table;
    public $timestamps = false;

    use Notifiable, HasApiTokens;

    protected $fillable = [
        'user_login', 'user_email', 'user_pass', 'user_nicename', 'user_url',
        'user_activation_key', 'user_status', 'display_name', 'user_registered',
    ];

    protected $hidden = [
        'user_pass', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = env('WP_DB_PREFIX') . 'users';
        parent::__construct($attributes);
    }

    public function getId(): int
    {
        return $this->ID;
    }

    public function getUsername(): string
    {
        return $this->user_login;
    }

    public function getEmail(): string
    {
        return $this->user_email;
    }

    public function getAuthPassword(): string
    {
        return $this->user_pass;
    }

    public function getUserUrl(): string
    {
        return $this->user_url;
    }

    public function getUserActivationKey(): string
    {
        return $this->user_activation_key;
    }

    public function getUserStatus(): int
    {
        return $this->user_status;
    }

    public function getUserRegistered(): string
    {
        return $this->user_registered;
    }

    public function getRememberToken(): string
    {
        return $this->remember_token;
    }

    /** This function is needed to make Passport get the user via the column user_email instead of the default email. */
    public function findForPassport(string $useremail): User
    {
        return User::where('user_email', $useremail)->first();
    }
}
