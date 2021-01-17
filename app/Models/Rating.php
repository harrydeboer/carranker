<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getModel(): Model
    {
        return $this->belongsTo(Model::class, 'model_id')->first();
    }

    public function getTrim(): Trim
    {
        return $this->belongsTo(Trim::class, 'trim_id')->first();
    }

    public function getUser(): User
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
