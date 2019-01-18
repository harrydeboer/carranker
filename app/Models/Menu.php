<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function getPages(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Page', 'menus_pages');
    }

    public function getName(): string
    {
        return $this->name;
    }
}