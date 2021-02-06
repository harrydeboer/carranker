<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Rating extends BaseModel
{
    use HasFactory;
    use Aspects;
    use ContentTrait;

    protected $table = 'ratings';
    public $timestamps = false;
    protected $fillable = ['user_id', 'model_id', 'trim_id', 'time', 'content', 'pending'];

    /**
     * The aspects are merged with the fillable property.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, $this->fillable);

        parent::__construct($attributes);
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

    /**
     * @return User
     */
    public function getUser(): EloquentModel
    {
        return $this->belongsTo(User::class, 'user_id')->first();
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getDate(): string
    {
        return date('d-m-Y', $this->time);
    }

    public function getPending(): int
    {
        return $this->pending;
    }

    public function setPending(int $pending)
    {
        $this->pending = $pending;
    }
}
