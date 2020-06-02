<?php

declare(strict_types=1);

namespace App\Models;

use App\CarSpecs;
use Illuminate\Database\Eloquent\Collection;

class Trim extends BaseModel
{
    use TrimTrait;
    use Aspect;
    use Spec;

    protected $table = 'trims';
    public static $hasTrimVersions = 0;
    public $timestamps = false;
    private $rating;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model_id', 'name', 'make', 'model', 'price', 'votes', 'year_begin', 'year_end'];

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
            if ($model->getName() !== $attributes['model']) {
                throw new \Exception("The model_id does not match the modelname.");
            }
            if ($model->getMakename() !== $attributes['make']) {
                throw new \Exception("The model_id does not match the makename.");
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
}
