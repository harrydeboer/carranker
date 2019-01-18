<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    protected $table = 'makes';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'content', 'wiki_car_make'];

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
        return $this->hasMany('\App\Models\Model', 'make_id', 'id')->get();
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        /** All content is translated to ISO-8859-1 en if a character gets an � it is removed. */
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }

    public function getImage(): string
    {
        $root = dirname(__DIR__, 2);
        $this->image = '/img/makes/';
        $this->image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($this->getName()))) . '.png';

        if (!file_exists($root . '/public/' . $this->image)) {
            $this->image = '';
        }

        return $this->image;
    }
}
