<?php

declare(strict_types=1);

namespace App\Models;

trait MakeTrait
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getWikiCarMake(): string
    {
        return $this->wiki_car_make ?? str_replace(' ', '_', $this->getName());
    }

    /** The image name of a make has no special characters so these are removed in the image url. */
    public function getImage(): string
    {
        $root = dirname(__DIR__, 2);
        $this->image = '/img/makes/';
        $this->image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i", "$1",
                htmlentities($this->getName()))) . '.png';

        if (!file_exists($root . '/public/' . $this->image)) {
            $this->image = '';
        }

        return $this->image;
    }

    public function getUrl(): string
    {
        return route('makePage', [
            'make' => rawurlencode($this->getName())]);
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
