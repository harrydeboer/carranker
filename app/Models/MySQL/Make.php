<?php

declare(strict_types=1);

namespace App\Models\MySQL;

use App\Models\Traits\MakeTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Make extends AbstractModel
{
    use HasFactory;
    use MakeTrait;

    protected $table = 'makes';
    public $timestamps = false;
    protected $fillable = ['name', 'content', 'wiki_car_make'];

    public function getCarModels(): Collection
    {
        return $this->hasMany(Model::class)->get();
    }

    public function save(array $options = []): bool
    {
        if (is_null($this->findId())) {
            $action = 'create';
        } else {
            $action = 'update';
        }

        $hasSaved = parent::save($options);

        $job = new ElasticsearchJob(['make_id' => $this->getId(), 'action' => $action]);

        $job->save();

        return $hasSaved;
    }

    public static function destroy($ids): int
    {
        $job = new ElasticsearchJob(['make_id' => $ids, 'action' => 'delete']);

        $job->save();

        return parent::destroy($ids);
    }
}
