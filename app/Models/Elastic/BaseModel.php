<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use \Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $repoClassName;
    protected $fillable = [];
    public $keywords = [];
    public $texts = [];
    public $integers = [];
    public $doubles = [];
    public $timestamps = [];
    public $booleans = [];

    public function __construct(array $attributes = [])
    {
        if ($attributes !== []) {
            foreach ($attributes as $key => $attribute) {
                $this->$key = $attribute;
            }
        }

        $this->fillable = array_merge($this->keywords, $this->texts, $this->integers, $this->doubles);

        $classNameArray = explode('\\', static::class);
        $this->repoClassName = 'App\Repositories\Elastic\\' . end($classNameArray) . 'Repository';

        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $repo = new $this->repoClassName();

        return $repo->hasMany($related, $foreignKey, $this->id);
    }

    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $repo = new $this->repoClassName();

        return $repo->hasOne($related, $foreignKey, $this->$localKey);
    }

    public function getMappings(): array
    {
        $mappings = [
            '_source' => [
                'enabled' => true,
            ],
        ];

        foreach ($this->keywords as $keyword) {
            $mappings['properties'][$keyword] = ['type' => 'keyword'];
        }

        foreach ($this->texts as $text) {
            $mappings['properties'][$text] = ['type' => 'text'];
        }

        foreach ($this->integers as $integer) {
            $mappings['properties'][$integer] = ['type' => 'integer'];
        }

        foreach ($this->doubles as $double) {
            $mappings['properties'][$double] = ['type' => 'double'];
        }

        foreach ($this->timestamps as $timestamp) {
            $mappings['properties'][$timestamp] = ['type' => 'date'];
        }

        foreach ($this->booleans as $boolean) {
            $mappings['properties'][$boolean] = ['type' => 'boolean'];
        }

        return $mappings;
    }
}