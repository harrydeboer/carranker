<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Mail\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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

    public function getRememberToken(): string
    {
        return $this->remember_token;
    }

    /** This function is needed to make Passport get the user via the column user_email instead of the default email. */
    public function findForPassport(string $useremail): User
    {
        return User::where('user_email', $useremail)->first();
    }

    public function getEmailForPasswordReset()
    {
        return $this->getEmail();
    }

    public function sendPasswordResetNotification($token)
    {
        $mailer = app()->make('Illuminate\Mail\Mailer');
        try {
            $mailer->send('auth.message', ['token' => $token, 'url' => env('APP_URL')],
                function (Message $message)
            {
                $message->from(env('MAIL_POSTMASTER_USERNAME'), 'Postmaster');
                $message->replyTo('noreply@carranker.com', 'No Reply');
                $message->subject('Password Reset Link');
                $message->to($this->getEmail());
            });

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function sendEmailVerificationNotification()
    {
        $mailer = app()->make('Illuminate\Mail\Mailer');
        try {
            $mailer->send('auth.verifyMessage', [
                'url' => env('APP_URL'),
                'id' => (string) $this->getId(),
                'hash' => sha1($this->getEmailForVerification()),
            ],
                function (Message $message)
                {
                    $message->from(env('MAIL_POSTMASTER_USERNAME'), 'Postmaster');
                    $message->replyTo('noreply@carranker.com', 'No Reply');
                    $message->subject('Email Verification Link');
                    $message->to($this->getEmail());
                });

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function getEmailForVerification()
    {
        return $this->getEmail();
    }
}
