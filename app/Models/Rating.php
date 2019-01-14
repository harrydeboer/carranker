<?php

declare(strict_types=1);

namespace App\Models;

class Rating extends BaseModel
{
    use Aspect;
    protected $table = 'ratings';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'model_id', 'trim_id', 'time', 'content'];

    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, $this->fillable);
        parent::__construct($attributes);
    }

    public function getModel()
    {
        return $this->hasOne('\App\Models\Model', 'id', 'model_id')->first();
    }

    public function getTrim()
    {
        return $this->hasOne('\App\Models\Trim', 'id', 'trim_id')->first();
    }

    public function getUser()
    {
        return $this->hasOne('\App\User', 'ID', 'user_id')->first();
    }

    public function getTime()
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

    public function setContent(string $content=null): Rating
    {
        $this->content = $content;

        return $this;
    }
}
