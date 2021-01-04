<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Make extends BaseModel
{
    use HasFactory;
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

    public function save(array $options = [])
    {
        $job = new ElasticJob(['make_id' => $this->getId(), 'action' => 'update']);

        $job->save();

        return parent::save($options);
    }
}
