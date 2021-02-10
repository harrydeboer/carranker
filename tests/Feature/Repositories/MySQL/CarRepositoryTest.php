<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\AspectsTrait;
use App\Models\MySQL\Model;
use App\Models\MySQL\Rating;
use App\Models\MySQL\Trim;
use App\Repositories\Interfaces\ModelWriteRepositoryInterface;
use App\Repositories\Interfaces\TrimWriteRepositoryInterface;
use Tests\FeatureTestCase;

class CarRepositoryTest extends FeatureTestCase
{
    private ModelWriteRepositoryInterface $modelRepository;
    private TrimWriteRepositoryInterface $trimRepository;
    private $trim;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modelRepository = $this->app->make(ModelWriteRepositoryInterface::class);
        $this->trimRepository = $this->app->make(TrimWriteRepositoryInterface::class);
        $this->model = Model::factory()->create();
        $this->trim = Trim::factory()->create([
                                                  'model_id' => $this->model->getId(),
                                                  'model_name' => $this->model->getName(),
                                                  'make_name' => $this->model->getMakeName(),
                                              ]);
    }

    public function testUpdateVotesAndRating()
    {
        $rating = Rating::factory()->create(['model_id' => $this->model->getId(), 'trim_id' => $this->trim->getId()]);

        $ratingArray = [];
        $modelRatingBeforeUpdate = [];
        $newRating = [];
        $newRatingWithEarlier = [];
        foreach (AspectsTrait::getAspects() as $aspect) {
            $ratingArray[$aspect] = 8;
            $modelRatingBeforeUpdate[$aspect] = $this->model->getAspect($aspect);
            $newRating[$aspect] = ($modelRatingBeforeUpdate[$aspect] * $this->model->getVotes() + $ratingArray[$aspect])
                / ($this->model->getVotes() + 1);
            $trimRatingBeforeUpdate[$aspect] = $this->trim->getAspect($aspect);
            $newRatingWithEarlier[$aspect] = ($trimRatingBeforeUpdate[$aspect] * $this->trim->getVotes() +
                    $ratingArray[$aspect] - $rating->getAspect($aspect)) / ($this->trim->getVotes());
        }

        $this->trimRepository->updateVotesAndRating($this->trim, $ratingArray, $rating);
        $trim = $this->trimRepository->get($this->trim->getId());
        foreach (AspectsTrait::getAspects() as $aspect) {
            $this->assertEquals((float) $trim->$aspect, $newRatingWithEarlier[$aspect]);
        }

        $this->modelRepository->updateVotesAndRating($this->model, $ratingArray, null);
        $model = $this->modelRepository->get($this->model->getId());
        foreach (AspectsTrait::getAspects() as $aspect) {
            $this->assertEquals((float) $model->$aspect, $newRating[$aspect]);
        }
    }
}
