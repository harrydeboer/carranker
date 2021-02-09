<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\API;

use App\Models\Elasticsearch\Trim;
use App\Models\MySQL\User;
use App\Repositories\Interfaces\TrimRepositoryInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Laravel\Passport\Passport;
use Tests\FeatureTestCase;

class APITest extends FeatureTestCase
{
    private User $user;
    private Trim $trim;

    protected function setUp(): void
    {
        parent::setUp();

        $hasher = $this->app->make(Hasher::class);
        $this->user = User::factory()->create(['password' => $hasher->make('password')]);

        $trimEloquent = \App\Models\MySQL\Trim::factory()->create();
        $this->artisan('index:cars')->execute();
        $trimRepository = $this->app->make(TrimRepositoryInterface::class);
        $this->trim = $trimRepository->get($trimEloquent->getId());
    }

    public function testOauthLoginAndGetMakeModelTrim()
    {
        Passport::actingAs($this->user);

        $trim = $this->trim;
        $model = $trim->getModel();
        $make = $model->getMake();

        $response = $this->json('GET','/api/make/' . $make->getId());

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());
        $this->assertEquals($make->getId(), $jsonObject->id);
        $this->assertEquals($make->getName(), $jsonObject->name);
        $this->assertEquals($make->getContent(), $jsonObject->content);

        $response = $this->json('GET','/api/model/' . $model->getId());

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($model->getId(), $jsonObject->id);
        $this->assertEquals($model->getName(), $jsonObject->name);
        $this->assertEquals($model->getContent(), $jsonObject->content);

        $response = $this->json('GET','/api/trim/' . $trim->getId());

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($trim->getId(), $jsonObject->id);
        $this->assertEquals($trim->getName(), $jsonObject->name);
    }

    public function testGetModelNames()
    {
        $make = $this->trim->getModel()->getMake();
        $models = $make->getModels();

        $response = $this->get('/api/getModelNames/' . $make->getName());
        $response->assertStatus(200);

        $array = [];
        foreach ($models as $model) {
            $array[] = $model->getName();
        }

        $this->assertEquals($response->getContent(), json_encode($array));
    }
}
