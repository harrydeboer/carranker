<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

use App\CarSpecs;
use CarrankerAdmin\App\Models\Make;
use CarrankerAdmin\App\Models\Model;

class TrimForm extends Form
{
    public $textFields = ['name' => ''];
    public $integerFields = ['votes' => 0, 'year_begin' => 0, 'year_end' => 0];
    public $floatFields = ['price' => 0, 'design' => 0, 'costs' => 0, 'comfort' => 0, 'reliability' => 0, 'performance' => 0,
        'max_trunk_capacity' => 0, 'engine_capacity' => 0, 'fueltank_capacity' => 0,
        'max_speed' => 0, 'full_weight' => 0, 'engine_power' => 0, 'acceleration' => 0, 'fuel_consumption' => 0];
    public $hiddenFields = ['id' => 0, 'carrankerAdminAction' => 'create'];
    public $selectFields = ['make' => '', 'model' => '', 'framework' => '', 'fuel' => '',
        'number_of_doors' => 0, 'number_of_seats' => 0, 'gearbox_type' => '', 'number_of_gears' => 0];
    public $selectChoices = ['make' => [], 'model' => [], 'framework' => [], 'fuel' => [],
        'number_of_doors' => [], 'number_of_seats' => [], 'gearbox_type' => [], 'number_of_gears' => []];

    public function __construct(string $createOrUpdate, object $request = null)
    {
        parent::__construct($createOrUpdate, $request);
        $this->selectChoices['make'] = Make::getMakenames();

        require_once dirname(__DIR__,6) . '/app/CarSpecs.php';

        foreach (CarSpecs::specsChoice() as $specname => $spec) {
            foreach ($spec['choices'] as $choice) {
                $this->selectChoices[$specname][] = $choice;
            }
        }
    }

    public function rules()
    {
        return [
            'name' => 'string|nullable',
            'make' => 'string|required',
            'model' => 'string|required',
            'votes' => 'integer|required',
            'year_begin' => 'integer|required',
            'year_end' => 'integer|required',
            'price' => 'float|required',
            'design' => 'float|required',
            'costs' => 'float|required',
            'comfort' => 'float|required',
            'reliability' => 'float|required',
            'performance' => 'float|required',
            'framework' => 'string|required',
            'fuel' => 'string|required',
            'number_of_doors' => 'integer|required',
            'number_of_seats' => 'integer|required',
            'max_trunk_capacity' => 'integer|required',
            'engine_capacity' => 'float|required',
            'fueltank_capacity' => 'integer|required',
            'max_speed' => 'integer|required',
            'full_weight' => 'integer|required',
            'engine_power' => 'integer|required',
            'acceleration' => 'float|required',
            'fuel_consumption' => 'float|required',
            'gearbox_type' => 'string|required',
            'number_of_gears' => 'integer|required',
        ];
    }

    public function validate(object $request)
    {
        parent::validate($request);

        if (!in_array($request->make, Make::getMakenames())) {
            $this->errors['make'] = 'The make does not exist.';
            return false;
        }
        if (!in_array($request->model, Model::getModelnames())) {
            $this->errors['model'] = 'The model does not exist.';
            return false;
        }
        foreach (CarSpecs::specsChoice() as $specname => $spec) {
            $this->errors[$specname] = 'The spec item ' . $specname . ' does not exist.';
            foreach ($spec['choices'] as $choice) {
                if (is_int($choice)) {
                    $request->$specname = (int) $request->$specname;
                } elseif (is_bool($choice)) {
                    $request->$specname = (bool) $request->$specname;
                } elseif (is_float($choice)) {
                    $request->$specname = (float) $request->$specname;
                }
                if ($choice === $request->$specname) {
                    unset($this->errors[$specname]);
                    break;
                }
            }
        }
        if (empty($this->errors)) {
            return true;
        }
    }
}