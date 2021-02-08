<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ElasticsearchJob extends BaseModel
{
    protected $table = 'elasticsearch_jobs';
    public $timestamps = false;
    protected $fillable = ['make_id', 'model_id', 'trim_id', 'action'];

    /**
     * @return Make
     */
    public function getMake(): EloquentModel
    {
        return $this->belongsTo(Make::class, 'make_id')->first();
    }

    /**
     * @return Model
     */
    public function getModel(): EloquentModel
    {
        return $this->belongsTo(Model::class, 'model_id')->first();
    }

    /**
     * @return Trim
     */
    public function getTrim(): EloquentModel
    {
        return $this->belongsTo(Trim::class, 'trim_id')->first();
    }
}
