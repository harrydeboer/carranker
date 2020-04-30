<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

abstract class BaseModel
{
    public function __construct(object $constrObj=null)
    {
        if (!is_null($constrObj)) {
            foreach ($constrObj as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProperties()
    {
        $properties = new \stdClass();
        foreach ($this as $key => $property) {
            $properties->$key = $property;
        }

        return $properties;
    }

    public static function getById(int $id): ?BaseModel
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . static::$table . " WHERE id={$id}");

        $result = self::sanitize_result($result, $wpdb);

        $className = static::class;

        return new $className($result[0]);
    }

    public static function findByName(string $name): ?BaseModel
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . static::$table . " WHERE name='{$name}'");

        if ($result === []) {
            return null;
        }

        $result = self::sanitize_result($result, $wpdb);

        $className = static::class;

        return new $className($result[0]);
    }

    public function create()
    {
        global $wpdb;

        $updateArray = [];
        foreach ($this as $key => $item) {
            if ($key !== 'id') {
                $updateArray[$key] = $item;
            }
        }

        $wpdb->insert(static::$table, $updateArray);
        $this->id = $wpdb->insert_id;

        $object = self::getById($this->id);
        foreach ($object as $key => $property) {
            $this->$key = $property;
        }
    }

    public function update()
    {
        global $wpdb;

        $updateArray = [];
        foreach ($this as $key => $item) {
            if ($key !== 'id') {
                $updateArray[$key] = $item;
            }
        }

        $wpdb->update(static::$table, $updateArray, ['id' => $this->id]);

        $object = self::getById((int) $this->id);
        foreach ($object as $key => $property) {
            $this->$key = $property;
        }
    }

    public static function delete(int $id)
    {
        global $wpdb;

        $wpdb->query("DELETE FROM " . static::$table . " WHERE id={$id}");
    }

    /** For query results the data type is asked from wpdb. For certain types there needs to be a cast to the
     * correct type since mysqli fetches all columns as strings.
     */
    protected static function sanitize_result($result, $wpdb)
    {
        while ($field = $wpdb->result->fetch_field()) {
            foreach ($result as $key => $row) {
                if (in_array($field->type, [1, 2, 9, 3, 8])) {
                    $result[$key]->{$field->name} = (int)$row->{$field->name};
                } elseif (in_array($field->type, [5, 246])) {
                    $result[$key]->{$field->name} = (double)$row->{$field->name};
                } elseif ($field->type === 4) {
                    $result[$key]->{$field->name} = (float)$row->{$field->name};
                }
            }
        }

        return $result;
    }
}