<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\ElasticClient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

abstract class BaseModel
{
    use HasAttributes;

    protected static $client;
    protected static string $index;

    protected $fillable = [];
    public $timestamps = [];
    public array $keywords = [];
    public array $texts = [];
    public array $integers = [];
    public array $doubles = [];
    public array $booleans = [];

    public function __construct(array $attributes = [])
    {
        self::$client = ElasticClient::getClient();

        if ($attributes !== []) {
            foreach ($attributes as $key => $attribute) {
                $this->$key = $attribute;
            }
        }

        $this->fillable = array_merge($this->keywords, $this->texts, $this->integers,
            $this->doubles, $this->timestamps, $this->booleans);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getIndex(): string
    {
        $index = static::$index;
        if (env('APP_ENV') === 'acceptance') {
            $index = 'accept' . $index;
        } elseif (env('APP_ENV') === 'testing') {
            $index = 'test' . $index;
        }

        return $index;
    }

    public static function get(int $id): BaseModel
    {
        $params = [
            'index' => static::getIndex(),
            'id' => $id,
        ];

        return self::arrayToModel(self::$client->get($params));
    }

    public static function searchOne(array $params): ?BaseModel
    {
        $result = self::$client->search($params);
        if (isset($result['hits']['hits'][0])) {
            $array = $result['hits']['hits'][0];
            $result['hits']['hits'] = $array;
        } else {
            return null;
        }

        return self::arrayToModel($result);
    }

    public static function searchMany(array $params, string $sortField=null): Collection
    {
        return self::arrayToModels(self::$client->search($params), $sortField);
    }

    public static function updateInIndex(array $params)
    {
        self::$client->update($params);
    }

    public static function deleteFromIndex(array $params)
    {
        self::$client->delete($params);
    }

    public static function indicesCreate(array $params): void
    {
        self::$client->indices()->create($params);
    }

    public static function indicesExists(array $params): bool
    {
        return self::$client->indices()->exists($params);
    }

    public static function indicesDelete(array $params): void
    {
        self::$client->indices()->delete($params);
    }

    public static function indicesGetMapping(array $params): array
    {
        return self::$client->indices()->getMapping($params);
    }

    public static function bulk(array $params): void
    {
        self::$client->bulk($params);
    }

    protected static function arrayToModel(array $result): BaseModel
    {
        $className = static::class;
        if (isset($result['hits']['hits'])) {
            $result = $result['hits']['hits'];
        }

        $fillable = array_merge(['id' => (int) $result['_id']], $result['_source']);

        return new $className($fillable);
    }

    protected static function arrayToModels(array $results, ?string $sortField): Collection
    {
        $models = [];
        $results = $results['hits']['hits'];
        $className = static::class;
        foreach ($results as $result) {
            $fillable = array_merge(['id' => (int)$result['_id']], $result['_source']);
            if (!is_null($sortField)) {
                $fillable[$sortField] = $result['sort'][0];
            }
            $models[] = new $className($fillable);
        }

        return new Collection($models);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null): Collection
    {
        $params = [
            'index' => $related::getIndex(),
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match' => [
                        $foreignKey => $this->getId(),
                    ],
                ],
            ],
        ];

        return $related::searchMany($params);
    }

    public function hasOne($related, $foreignKey = null, $localKey = null): BaseModel
    {
        $params = [
            'index' => $related::getIndex(),
            'id' => $this->$localKey,
        ];

        return $related::arrayToModel(self::$client->get($params));
    }

    public function getSettings(): array
    {
        return [
            'analysis' => [
                'normalizer' => [
                    'normalizer_lowercase' => [
                        'type' => 'custom',
                        'char_filter' => [],
                        'filter' => ['lowercase'],
                    ]
                ]
            ]
        ];
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
            if ($keyword === 'name' || $keyword === 'make' || $keyword === 'model') {
                $mappings['properties'][$keyword]['normalizer'] = 'normalizer_lowercase';
            }
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
