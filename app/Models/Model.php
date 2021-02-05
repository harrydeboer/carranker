<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Model stands for car model instead of Laravel model.
 */
class Model extends BaseModel implements CarInterface
{
    use ModelTrait;
    use Aspects;
    use HasFactory;

    protected $table = 'models';
    public $timestamps = false;
    protected $fillable = ['make_id', 'name', 'make_name', 'content', 'price', 'votes', 'wiki_car_model'];

    /**
     * When a new model is made there is a check that the name of the make matches the make_id.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, $this->fillable);

        parent::__construct($attributes);

        if ($attributes !== []) {
            $make = (new Make())->find($attributes['make_id']);
            if ($make->getName() !== $attributes['make_name']) {
                throw new Exception("The make id does not match the make name.");
            }
        }
    }

    /**
     * @return Make
     */
    public function getMake(): EloquentModel
    {
        return $this->belongsTo(Make::class, 'make_id')->first();
    }

    public function getTrims(): Collection
    {
        return $this->hasMany(Trim::class)->get();
    }

    public function save(array $options = []): bool
    {
        if (is_null($this->findId())) {
            $action = 'create';
        } else {
            $action = 'update';
        }

        $hasSaved = parent::save($options);

        $job = new ElasticJob(['model_id' => $this->getId(), 'action' => $action]);

        $job->save();

        return $hasSaved;
    }

    public static function destroy($ids): int
    {
        $job = new ElasticJob(['model_id' => $ids, 'action' => 'delete']);

        $job->save();

        return parent::destroy($ids);
    }
}
