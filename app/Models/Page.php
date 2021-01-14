<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends BaseModel
{
    use HasFactory;

    protected $table = 'pages';
    public $timestamps = false;
    protected $fillable = ['name', 'title', 'content'];

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['content'])) {
            $this->setContent($attributes['content']);
        }

        parent::__construct($attributes);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        $content = mb_convert_encoding($this->content, 'ISO-8859-1', 'HTML-ENTITIES');
        $content = iconv("UTF-8", "UTF-8//IGNORE", $content);

        return $content;
    }

    public function setContent(string $content): void
    {
        $this->content = mb_convert_encoding($content, 'HTML-ENTITIES', 'ISO-8859-1');
    }

    /** The menus have multiple pages and the pages have multiple menus so these are many to many. */
    public function getMenus(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Menu', 'menus_pages');
    }
}
