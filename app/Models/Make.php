<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    use MakeTrait;
    
    protected $table = 'makes';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'content', 'wiki_car_make'];

    public function getModels(): Collection
    {
        return $this->hasMany('\App\Models\Model', 'make_id', 'id')->get();
    }
}
