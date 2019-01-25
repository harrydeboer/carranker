<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends BaseModel
{
    protected $table = 'pages';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'content'];

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /** The menus have multiple pages and the pages have multiple menus so these are many to many. */
    public function getMenus(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Menu', 'menus_pages');
    }
}