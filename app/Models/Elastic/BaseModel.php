<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Repositories\Elastic\Client;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected static $client;

    protected $fillable = [];
    public $keywords = [];
    public $texts = [];
    public $integers = [];
    public $doubles = [];
    public $timestamps = [];
    public $booleans = [];

    public function __construct(array $attributes = [])
    {
        self::$client = Client::getClient();

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

    public static function get(array $params, string $related=null): BaseModel
    {
        return self::arrayToModel(self::$client->get($params), $related);
    }

    public static function search(array $params, string $related=null): Collection
    {
        return self::arrayToModels(self::$client->search($params), $related);
    }

    public static function index(array $params)
    {
        self::$client->index($params);
    }

    public static function indicesCreate(array $params)
    {
        self::$client->indices()->create($params);
    }

    public static function indicesExists(array $params)
    {
        return self::$client->indices()->exists($params);
    }

    public static function indicesDelete(array $params)
    {
        self::$client->indices()->delete($params);
    }

    public static function indicesGetMapping(array $params)
    {
        return self::$client->indices()->getMapping($params);
    }

    public static function bulk(array $params)
    {
        self::$client->bulk($params);
    }

    protected static function arrayToModel(array $result, string $related=null): BaseModel
    {
        $className = $related ?? static::class;
        if (isset($result['hits']['hits'])) {
            $result = $result['hits']['hits'];
        }
        $fillable = array_merge(['id' => (int) $result['_id']], $result['_source']);

        return new $className($fillable);
    }

    protected static function arrayToModels(array $results, string $related=null): Collection
    {
        $models = [];
        $results = $results['hits']['hits'];
        $className = $related ?? static::class;
        foreach ($results as $result) {
            $fillable = array_merge(['id' => (int)$result['_id']], $result['_source']);
            $models[] = new $className($fillable);
        }

        return new Collection($models);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $classArray = explode('\\', $related);
        $index = strtolower(end($classArray)) . 's';

        $params = [
            'index' => $index,
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match' => [
                        $foreignKey => $this->id,
                    ],
                ],
            ],
        ];

        return self::search($params, $related);
    }

    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $classArray = explode('\\', $related);
        $index = strtolower(end($classArray)) . 's';

        $params = [
            'index' => $index,
            'id' => $this->$localKey,
        ];

        return self::get($params, $related);
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