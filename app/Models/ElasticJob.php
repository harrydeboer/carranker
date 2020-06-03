<?php

declare(strict_types=1);

namespace App\Models;

class ElasticJob extends BaseModel
{
    protected $table = 'elastic_jobs';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['make_id', 'model_id', 'trim_id', 'action'];

    public function getMake()
    {
        return $this->hasOne('\App\Models\Make', 'id', 'make_id')->first();
    }

    public function getModel()
    {
        return $this->hasOne('\App\Models\Model', 'id', 'model_id')->first();
    }

    public function getTrim()
    {
        return $this->hasOne('\App\Models\Trim', 'id', 'trim_id')->first();
    }
}