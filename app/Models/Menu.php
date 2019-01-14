<?php

declare(strict_types=1);

namespace App\Models;

class Menu extends BaseModel
{
    protected $table = 'menus';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function getPages()
    {
        return $this->belongsToMany('App\Models\Page', 'menus_pages');
    }

    public function getName()
    {
        return $this->name;
    }
}