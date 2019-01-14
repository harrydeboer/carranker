<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class Make extends BaseModel
{
    protected static $table = 'makes';

    protected $id;
    protected $name;
    protected $content;
    protected $wiki_car_make;

    public function getName(): string
    {
        return $this->name;
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

    public function getWikiCarMake()
    {
        return $this->wiki_car_make;
    }

    public function setWikiCarMake(?string $wikiCarMake)
    {
        $this->wiki_car_make = $wikiCarMake;
    }

    public static function getMakenames()
    {
        global $wpdb;

        $makenamesRaw = $wpdb->get_results('SELECT name FROM ' . self::$table);
        $makenames = [];
        foreach($makenamesRaw as $makename) {
            $makenames[] = $makename->name;
        }

        return $makenames;
    }
}