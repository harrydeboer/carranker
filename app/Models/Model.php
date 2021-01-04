<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel
{
    use ModelTrait;
    use Aspect;
    use HasFactory;

    protected $table = 'models';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['make_id', 'name', 'make_name', 'content',
        'price', 'votes', 'wiki_car_model'];

    /**
     * The aspects are merged with the fillable property.
     * When a new model is made there is a check that the name of the make matches the make_id. */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, $this->fillable);
        parent::__construct($attributes);

        if ($attributes !== []) {
            $make = Make::find($attributes['make_id']);
            if ($make->getName() !== $attributes['make_name']) {
                throw new \Exception("The make_id does not match the make name.");
            }
        }
    }

    public function getMake(): Make
    {
        return $this->hasOne('\App\Models\Make', 'id', 'make_id')->first();
    }

    public function getTrims(): Collection
    {
        return $this->hasMany('\App\Models\Trim','model_id', 'id')->get();
    }

    public function save(array $options = [])
    {
        if (is_null($this->getId())) {
            $action = 'create';
        } else {
            $action = 'update';
        }

        $hasSaved = parent::save($options);

        $job = new ElasticJob(['model_id' => $this->getId(), 'action' => $action]);

        $job->save();

        return $hasSaved;
    }

    public static function destroy($ids)
    {
        $job = new ElasticJob(['model_id' => $ids, 'action' => 'delete']);

        $job->save();

        return parent::destroy($ids);
    }
}
