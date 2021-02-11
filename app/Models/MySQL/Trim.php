<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use App\Models\Traits\AspectsTrait;
use App\Models\Traits\SpecTrait;
use App\Models\Traits\TrimTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exception;
use App\Parameters\CarSpecs;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * A trim is a type of car model (a specific generation/series).
 */
class Trim extends AbstractModel implements CarInterface
{
    use TrimTrait;
    use AspectsTrait;
    use SpecTrait;
    use HasFactory;

    protected $table = 'trims';
    public $timestamps = false;
    private float $rating;
    protected $fillable = ['model_id', 'name', 'make_name', 'model_name', 'price', 'votes', 'year_begin', 'year_end'];

    /**
     * The aspects and specs choice and specs range are merged with the fillable property.
     * When a new trim is made there is a check on the choices specs. They must match the choices in the CarSpecs class.
     * When a new trim is made the model_id has to match the model name and make name.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge(self::$aspects, self::$specsRange, self::$specsChoice, $this->fillable);
        parent::__construct($attributes);

        if ($attributes !== []) {
            foreach (CarSpecs::specsChoice() as $key => $spec) {
                $test = false;
                foreach ($spec['choices'] as $choice) {
                    if ($choice === $attributes[$key]) {
                        $test = true;
                        break;
                    }
                }
                if ($test === false) {
                    throw new Exception('The spec ' . $key . ' has been assigned a non existing item.');
                }
            }

            $model = (new Model())->find($attributes['model_id']);
            if ($model->getName() !== $attributes['model_name']) {
                throw new Exception("The model_id does not match the model name.");
            }
            if ($model->getMakeName() !== $attributes['make_name']) {
                throw new Exception("The model_id does not match the make name.");
            }
        }
    }

    public function getRatings(): Collection
    {
        return $this->hasMany(Rating::class)->get();
    }

    /**
     * @return Model
     */
    public function getModel(): EloquentModel
    {
        return $this->belongsTo(Model::class, 'model_id')->first();
    }

    public function save(array $options = []): bool
    {
        if (is_null($this->findId())) {
            $action = 'create';
        } else {
            $action = 'update';
        }

        $hasSaved = parent::save($options);

        $job = new ElasticsearchJob(['trim_id' => $this->getId(), 'action' => $action]);

        $job->save();

        return $hasSaved;
    }

    public static function destroy($ids): int
    {
        $job = new ElasticsearchJob(['trim_id' => $ids, 'action' => 'delete']);

        $job->save();

        return parent::destroy($ids);
    }
}
