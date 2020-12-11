<?php

declare(strict_types=1);

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailUser extends BaseModel
{
    use HasFactory;

    protected $table = 'mail_users';
    public $timestamps = false;

    protected $fillable = ['domain', 'password', 'email', 'forward'];

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getForward(): string
    {
        return $this->forward;
    }
}
