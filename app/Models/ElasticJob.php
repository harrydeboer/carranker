<?php

declare(strict_types=1);

namespace App\Models;

class ElasticJob extends BaseModel
{
    protected $table = 'elastic_jobs';
    public $timestamps = false;
    protected $fillable = ['make_id', 'model_id', 'trim_id', 'action'];

    public function getMake(): Make
    {
        return $this->belongsTo(Make::class, 'make_id')->first();
    }

    public function getModel(): Model
    {
        return $this->belongsTo(Model::class, 'model_id')->first();
    }

    public function getTrim(): Trim
    {
        return $this->belongsTo(Trim::class, 'trim_id')->first();
    }
}
