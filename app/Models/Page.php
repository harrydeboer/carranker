<?php

declare(strict_types=1);

namespace App\Models;

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

    public function getMenus()
    {
        return $this->belongsToMany('App\Models\Menu', 'menus_pages');
    }
}