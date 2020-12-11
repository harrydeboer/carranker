<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends BaseModel
{
    use HasFactory;
    use Aspect;
    protected $table = 'ratings';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'model_id', 'trim_id', 'time', 'content'];

    /** The aspects are merged with the fillable property. */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, $this->fillable);
        parent::__construct($attributes);
    }

    public function getModel(): Model
    {
        return $this->hasOne('\App\Models\Model', 'id', 'model_id')->first();
    }

    public function getTrim(): Trim
    {
        return $this->hasOne('\App\Models\Trim', 'id', 'trim_id')->first();
    }

    public function getUser(): User
    {
        return $this->hasOne('\App\Models\User', 'ID', 'user_id')->first();
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getContent(): string
    {
        return mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
    }

    public function getDate(): string
    {
        return date('d-m-Y', $this->time);
    }

    public function setContent(string $content=null): void
    {
        $this->content = $content;
    }
}
