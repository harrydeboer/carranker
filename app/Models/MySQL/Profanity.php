<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profanity extends AbstractModel
{
    use HasFactory;

    protected $table = 'profanities';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function getName(): string
    {
        return $this->name;
    }
}
