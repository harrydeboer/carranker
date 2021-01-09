<?php

declare(strict_types=1);

namespace App\Models;

use App\CarSpecs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trim extends BaseModel
{
    use TrimTrait;
    use Aspect;
    use Spec;
    use HasFactory;

    protected $table = 'trims';
    public static $hasTrimVersions = 0;
    public $timestamps = false;
    private float $rating;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
                    throw new \Exception('The spec ' . $key . ' has been assigned a non existing item.');
                }
            }

            $model = Model::find($attributes['model_id']);
            if ($model->getName() !== $attributes['model_name']) {
                throw new \Exception("The model_id does not match the model name.");
            }
            if ($model->getMakeName() !== $attributes['make_name']) {
                throw new \Exception("The model_id does not match the make name.");
            }
        }
    }

    public function getRatings(): Collection
    {
        return $this->hasMany('\App\Models\Rating','trim_id', 'id')->get();
    }

    public function getModel(): Model
    {
        return $this->hasOne('\App\Models\Model', 'id', 'model_id')->first();
    }

    public function save(array $options = [])
    {
        if (is_null($this->findId())) {
            $action = 'create';
        } else {
            $action = 'update';
        }

        $hasSaved = parent::save($options);

        $job = new ElasticJob(['trim_id' => $this->getId(), 'action' => $action]);

        $job->save();

        return $hasSaved;
    }

    public static function destroy($ids)
    {
        $job = new ElasticJob(['trim_id' => $ids, 'action' => 'delete']);

        $job->save();

        return parent::destroy($ids);
    }
}
