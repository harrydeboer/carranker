<?php

declare(strict_types=1);

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    protected $primaryKey = 'ID';
    protected $table = 'wp_users';
    public $timestamps = false;

    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_login', 'user_email', 'user_pass', 'user_nicename', 'user_url', 'user_activation_key', 'user_status', 'display_name', 'user_registered',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_pass', 'remember_token',
    ];

    public function getId()
    {
        return $this->ID;
    }

    public function getUsername()
    {
        return $this->user_login;
    }

    public function getRatings($modelOrTrim, $id)
    {
        if ($modelOrTrim === 'trim') {
            return $this->hasMany('\App\Models\Rating')->where('trim_id', $id)->first();
        }
        $ratings = [];
        foreach ($this->hasMany('\App\Models\Rating')->where('model_id', $id)->get() as $rating) {
            $ratings[$rating->getTrim()->getId()] = $rating;
        }
        return $ratings;
    }

    public function getEmail()
    {
        return $this->user_email;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    public function getUserUrl()
    {
        return $this->user_url;
    }

    public function getUserActivationKey()
    {
        return $this->user_activation_key;
    }

    public function getUserStatus()
    {
        return $this->user_status;
    }

    public function getUserRegistered()
    {
        return $this->user_registered;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }
}
