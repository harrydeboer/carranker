<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profanity extends BaseModel
{
    use HasFactory;

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
