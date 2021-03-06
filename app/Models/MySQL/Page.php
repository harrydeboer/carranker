<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use App\Models\Traits\ContentTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends AbstractModel
{
    use HasFactory;
    use ContentTrait;

    protected $table = 'pages';
    public $timestamps = false;
    protected $fillable = ['name', 'title', 'content'];

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

    /**
     * The menus have multiple pages and the pages have multiple menus so these are many to many.
     */
    public function getMenus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menus_pages');
    }
}
