<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\ElasticClient;
use Illuminate\Database\Eloquent\Collection;
use Elasticsearch\Client;
use App\Models\BaseModel as EloquentBaseModel;
use stdClass;

abstract class BaseModel
{
    protected static Client $client;
    protected static string $index;

    protected int $id;
    protected array $attributes;
    protected array $keywords = [];
    protected array $texts = [];
    protected array $integers = [];
    protected array $doubles = [];
    protected array $booleans = [];

    public function __construct(array $attributes = [])
    {
        self::$client = ElasticClient::getClient();

        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        if (method_exists($this, 'getAspects')) {
            foreach ($this->getAspects() as $aspect) {
                $this->doubles[] = $aspect;
            }
        }

        $this->attributes = $attributes;
    }

    public static function all(): Collection
    {
        $params = [
            'index' => static::getIndex(),
            'body' => [
                'query' => [
                    'match_all' => new stdClass()
                ]
            ]
        ];

        return static::searchMany($params);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function getDoubles(): array
    {
        return $this->doubles;
    }

    public function getBooleans(): array
    {
        return $this->booleans;
    }

    public function getIntegers(): array
    {
        return $this->integers;
    }

    public function getTexts(): array
    {
        return $this->texts;
    }

    public function getMappingFields(): array
    {
        return array_merge($this->keywords, $this->doubles, $this->booleans, $this->integers, $this->texts);
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

    public static function get(int $id): static
    {
        $params = [
            'index' => static::getIndex(),
            'id' => $id,
        ];

        return self::arrayToModel(self::$client->get($params));
    }

    public static function searchOne(array $params): ?static
    {
        $result = self::$client->search($params);
        if (isset($result['hits']['hits'][0])) {
            $result = $result['hits']['hits'][0];
        } else {
            return null;
        }

        return self::arrayToModel($result);
    }

    public static function searchMany(array $params, string $sortField=null): Collection
    {
        $result = self::$client->search($params);

        return self::arrayToModels($result['hits']['hits'], $sortField);
    }

    public function indexExists(): bool
    {
        return self::$client->indices()->exists(['index' => static::getIndex()]);
    }

    public function indexCreate(array $params): void
    {
        static::$client->indices()->create($params);
    }

    public function indexDelete(array $params): void
    {
        static::$client->indices()->delete($params);
    }

    public function indexGetMapping(array $params): array
    {
        return static::$client->indices()->getMapping($params);
    }

    public static function bulk(array $params): void
    {
        static::$client->bulk($params);
    }

    protected static function arrayToModel(array $result): static
    {
        $attributes = array_merge(['id' => (int) $result['_id']], $result['_source']);

        return new static($attributes);
    }

    protected static function arrayToModels(array $results, ?string $sortField): Collection
    {
        $models = [];
        foreach ($results as $result) {
            $attributes = array_merge(['id' => (int) $result['_id']], $result['_source']);
            if (!is_null($sortField)) {
                $attributes[$sortField] = $result['sort'][0];
            }
            $models[] = new static($attributes);
        }

        return new Collection($models);
    }

    public function hasMany($related, $foreignKey = null): Collection
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

    public function hasOne($related, $localKey = null): BaseModel
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

        foreach ($this->booleans as $boolean) {
            $mappings['properties'][$boolean] = ['type' => 'boolean'];
        }

        return $mappings;
    }

    public function propertiesToParams(EloquentBaseModel $model): array
    {
        $params = [];

        foreach ($this->keywords as $keyword) {
            $params[$keyword] = $model->$keyword;
        }

        foreach ($this->texts as $text) {
            $params[$text] = $model->$text;
        }

        foreach ($this->integers as $integer) {
            $params[$integer] = $model->$integer;
        }

        foreach ($this->doubles as $double) {
            $params[$double] = $model->$double;
        }

        foreach ($this->booleans as $boolean) {
            $params[$boolean] = $model->$boolean;
        }

        return $params;
    }
}
