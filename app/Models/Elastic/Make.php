<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    public $keywords = ['name', 'wiki_car_make'];
    public $texts = ['content'];

    protected $id;
    protected $name;
    protected $wiki_car_make;
    protected $content;

    public function getName(): string
    {
        return $this->name;
    }

    public function getWikiCarMake(): string
    {
        return $this->wiki_car_make ?? str_replace(' ', '_', $this->name);
    }

    public function getModels(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Model', 'make_id');
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        if (is_null($this->content)) {
            return null;
        }
        /** All content is translated to ISO-8859-1 en if a character gets an � it is removed. */
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }

    /** The image name of a make has no special characters so these are removed in the image url. */
    public function getImage(): string
    {
        $root = dirname(__DIR__, 3);
        $this->image = '/img/makes/';
        $this->image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i", "$1",
                htmlentities($this->getName()))) . '.png';

        if (!file_exists($root . '/public/' . $this->image)) {
            $this->image = '';
        }

        return $this->image;
    }
}