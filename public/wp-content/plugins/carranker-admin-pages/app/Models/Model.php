<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class Model extends BaseModel
{
    protected static $table = 'models';

    protected $id;
    protected $make_id;
    protected $name;
    protected $make;
    protected $content;
    protected $price;
    protected $votes;
    protected $wiki_car_model;
    protected $design;
    protected $costs;
    protected $comfort;
    protected $reliability;
    protected $performance;

    public function getName(): string
    {
        return $this->name;
    }

    public function getMake(): string
    {
        return $this->make;
    }

    public function getMakeId(): int
    {
        return (int) $this->make_id;
    }


    public function getContent()
    {
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        return iconv("UTF-8", "UTF-8//IGNORE", $content);
    }

    public function setContent(string $content)
    {
        $this->content = mb_convert_encoding($content, 'HTML-ENTITIES', 'ISO-8859-1');
    }

    public function setMakeId(int $makeId)
    {
        $this->make_id = $makeId;
    }

    public static function getByNames(string $makename, string $modelname)
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . self::$table . " WHERE name='{$modelname}' && make='{$makename}'");

        $result = self::sanitize_result($result, $wpdb);

        return empty($result) ? null : new Model($result[0]);
    }

    public static function getModelnames()
    {
        global $wpdb;

        $modelnamesRaw = $wpdb->get_results('SELECT name, make FROM ' . self::$table);
        $modelnames = [];
        foreach($modelnamesRaw as $modelname) {
            $modelnames[] = $modelname->make . ';' . $modelname->name;
        }

        return $modelnames;
    }
}