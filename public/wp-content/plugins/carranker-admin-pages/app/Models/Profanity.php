<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class Profanity extends BaseModel
{
    protected static $table = 'profanities';

    protected $id;
    protected $name;

    public static function all(string $firstCharacter): array
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . static::$table . " WHERE Left(name,1) ='$firstCharacter' ORDER BY name asc");

        $result = self::sanitize_result($result, $wpdb);

        $profanities = [];
        foreach ($result as $row) {
            $profanities[] = new Profanity($row);
        }

        return $profanities;
    }

    public function getName(): string
    {
        return $this->name;
    }
}