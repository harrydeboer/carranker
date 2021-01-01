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
    protected $primaryKey = 'ID';
    protected $table;
    public $timestamps = false;

    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'user_login', 'user_email', 'user_pass', 'user_nicename', 'user_url',
        'user_activation_key', 'user_status', 'display_name', 'user_registered', 'email_verified_at',
    ];

    protected $hidden = [
        'user_pass', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationFor($driver, $notification = null)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}($notification);
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->getEmail();
        }
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->email_verified_at;
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

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    /** This function is needed to make Passport get the user via the column user_email instead of the default email. */
    public function findForPassport(string $useremail): User
    {
        return User::where('user_email', $useremail)->first();
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

    public function getEmailForPasswordReset()
    {
        return $this->getEmail();
    }

    public function getEmailForVerification()
    {
        return $this->getEmail();
    }
}
