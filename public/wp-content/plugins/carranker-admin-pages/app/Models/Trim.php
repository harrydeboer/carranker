<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class Trim extends BaseModel
{
    protected static $table = 'trims';

    public static $hasTrimTypes = 0;

    protected $id;
    protected $name;
    protected $model_id;
    protected $make;
    protected $model;
    protected $year_begin;
    protected $year_end;
    protected $design;
    protected $costs;
    protected $comfort;
    protected $reliability;
    protected $performance;
    protected $price;
    protected $votes;
    protected $framework;
    protected $fuel;
    protected $number_of_doors;
    protected $number_of_seats;
    protected $max_trunk_capacity;
    protected $engine_capacity;
    protected $fueltank_capacity;
    protected $max_speed;
    protected $full_weight;
    protected $number_of_gears;
    protected $gearbox_type;
    protected $engine_power;
    protected $acceleration;
    protected $fuel_consumption;

    public function getMake()
    {
        return $this->make;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelId(): int
    {
        return (int) $this->model_id;
    }

    public function setModelId(int $modelId)
    {
        $this->model_id = $modelId;
    }

    public function getYearBegin()
    {
        return $this->year_begin;
    }

    public function getYearEnd()
    {
        return $this->year_end;
    }

    /** For the front-end it is handy to have all the generations, series and trims of the trims in one array. */
    public static function getGenerationsSeriesTrims(Model $model): array
    {
        global $wpdb;

        $trims = $wpdb->get_results("SELECT * FROM " . self::$table . " WHERE model_id=" . $model->getId());

        $generationsSeriesTrims = [];
        foreach ($trims as $trim) {

            /** If the trim has a name it means the trim has a specific trim version.
             * Their names are stored as a key in the generationsSeriesTrims array. */
            if ($trim->name) {
                self::$hasTrimTypes = 1;
                $generationsSeriesTrims[$trim->year_begin . '-' . $trim->year_end][$trim->framework][$trim->name] = $trim->id;
            } else {
                $generationsSeriesTrims[$trim->year_begin . '-' . $trim->year_end][$trim->framework][] = $trim->id;
            }
        }
        krsort($generationsSeriesTrims);

        return $generationsSeriesTrims;
    }
}