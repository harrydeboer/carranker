<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends BaseModel
{
    use HasFactory;

    protected $table = 'menus';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /** The menus have multiple pages and the pages have multiple menus so these are many to many. */
    public function getPages(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Page', 'menus_pages');
    }

    public function getName(): string
    {
        return $this->name;
    }
}
