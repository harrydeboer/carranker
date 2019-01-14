<?php

declare(strict_types=1);

namespace App\Models;

class Profanity extends BaseModel
{
    protected $table = 'profanities';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function getName(): string
    {
        return $this->name;
    }
}