<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Aspect;
use App\Repositories\ModelRepository;
use App\Repositories\TrimRepository;
use Tests\TestCase;
use App\Models\Model;
use App\Models\Trim;
use App\Models\Rating;

class CarRepositoryTest extends TestCase
{
    private ModelRepository $modelRepository;
    private TrimRepository $trimRepository;
    private $trim;
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->modelRepository = $this->app->make(ModelRepository::class);
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->model = Model::factory()->create();
        $this->trim = Trim::factory()->create([
            'model_id' => $this->model->getId(),
            'model' => $this->model->getName(),
            'make' => $this->model->getMakename(),
            ]);
    }

    public function testUpdateVotesAndRating()
    {
        $rating = Rating::factory()->create(['model_id' => $this->model->getId(), 'trim_id' => $this->trim->getId()]);

        $ratingArray = [];
        $modelRatingBeforeUpdate = [];
        $newRating = [];
        foreach (Aspect::getAspects() as $aspect) {
            $ratingArray[$aspect] = 8;
            $modelRatingBeforeUpdate[$aspect] = $this->model->getAspect($aspect);
            $newRating[$aspect] = ($modelRatingBeforeUpdate[$aspect] * $this->model->getVotes() + $ratingArray[$aspect]) /
                ($this->model->getVotes() + 1);
            $trimRatingBeforeUpdate[$aspect] = $this->trim->getAspect($aspect);
            $newRatingWithEarlier[$aspect] = ($trimRatingBeforeUpdate[$aspect] * $this->trim->getVotes() +
                    $ratingArray[$aspect] - $rating->getAspect($aspect)) / ($this->trim->getVotes());
        }

        $this->trimRepository->updateVotesAndRating($this->trim, $ratingArray, $rating);
        $trim = $this->trimRepository->get($this->trim->getId());
        foreach (Aspect::getAspects() as $aspect) {
            $this->assertEquals((float) $trim->$aspect, $newRatingWithEarlier[$aspect]);
        }

        $this->modelRepository->updateVotesAndRating($this->model, $ratingArray, null);
        $model = $this->modelRepository->get($this->model->getId());
        foreach (Aspect::getAspects() as $aspect) {
            $this->assertEquals((float) $model->$aspect, $newRating[$aspect]);
        }
    }
}
