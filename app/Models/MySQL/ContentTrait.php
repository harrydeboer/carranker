<?php

declare(strict_types=1);

namespace App\Models\MySQL;

/**
 * When a model has a content field the content must be encoded to the database and decoded from the database.
 */
trait ContentTrait
{
    public function setContent(?string $content): void
    {
        if (is_null($content)) {
            $this->content = null;
        } else {
            $this->content = mb_convert_encoding($content, 'HTML-ENTITIES', 'ISO-8859-1');
        }
    }

    public function getContent(): ?string
    {
        if (is_null($this->content)) {
            return null;
        }
        /**
         * All content is translated to ISO-8859-1 and if a character gets an � it is removed.
         */
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }
}
