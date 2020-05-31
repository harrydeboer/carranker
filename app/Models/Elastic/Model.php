<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;
use Illuminate\Database\Eloquent\Collection;

class Model extends BaseModel
{
    use Aspect;
    public $keywords = ['name', 'make', 'wiki_car_model'];
    public $texts = ['content'];
    public $integers = ['make_id', 'votes'];
    public $doubles = ['price'];

    public function __construct(array $attributes = [])
    {
        foreach (Aspect::getAspects() as $aspect) {
            $this->doubles[] =$aspect;
        }
        $this->fillable = array_merge(self::$aspects, $this->fillable);
        parent::__construct($attributes);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMake(): Make
    {
        return $this->hasOne('\App\Models\Elastic\Make', 'id', 'make_id');
    }

    public function getTrims(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Trim','model_id');
    }

    public function getMakename(): string
    {
        return $this->make;
    }

    public function getPrice(float $FXRate): ?float
    {
        return $this->price * $FXRate;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    /** The images are stored in directories with names that contain no special characters.
     * To get the image url the special characters must be replaced by normal characters. */
    public function getImage(): string
    {
        $image = '/img/models/';
        $image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities(str_replace('/', '', $this->make)))) . '_';
        $image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities(str_replace('/', '', $this->getName())))) . '.jpg';

        $root = dirname(__DIR__, 3);
        if (!file_exists($root . '/public/' . $image)) {
            $image = '';
        }

        return $image;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /** All content is translated to ISO-8859-1 en if a character gets an � it is removed. */
    public function getContent(): ?string
    {
        if (is_null($this->content)) {
            return null;
        }

        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }

    public function getWikiCarModel(): string
    {
        if (empty($this->wiki_car_model)) {
            return str_replace(' ', '_', $this->getMakename() . '_' . $this->getName());
        }

        return str_replace(' ', '_', $this->getMakename()) . '_' . $this->wiki_car_model;
    }

    public function setVotes(int $votes): void
    {
        $this->votes = $votes;
    }
}