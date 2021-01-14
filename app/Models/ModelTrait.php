<?php

declare(strict_types=1);

namespace App\Models;

trait ModelTrait
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getMakeName(): string
    {
        return $this->make_name;
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
                "$1", htmlentities(str_replace('/', '', $this->getMakeName())))) . '_';
        $image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities(str_replace('/', '', $this->getName())))) . '.jpg';

        $root = dirname(__DIR__, 2);
        if (!file_exists($root . '/public/' . $image)) {
            $image = '';
        }

        return $image;
    }

    public function getUrl(): string
    {
        return route('modelPage', [
                'make' => rawurlencode($this->getMakeName()),
                'model' => rawurlencode($this->getName())]);
    }

    public function setContent(string $content): void
    {
        $this->content = mb_convert_encoding($content, 'HTML-ENTITIES', 'ISO-8859-1');
    }

    public function getContent(): ?string
    {
        if (is_null($this->content)) {
            return null;
        }
        /** All content is translated to ISO-8859-1 and if a character gets an � it is removed. */
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }

    public function getWikiCarModel(): string
    {
        if (empty($this->wiki_car_model)) {
            return str_replace(' ', '_', $this->getMakeName() . '_' . $this->getName());
        }

        return str_replace(' ', '_', $this->getMakeName()) . '_' . $this->wiki_car_model;
    }

    public function setVotes(int $votes): void
    {
        $this->votes = $votes;
    }
}
