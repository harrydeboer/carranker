<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\User;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class APITest extends TestCase
{
    private $user;
    private UserRepository $userRepository;
    private MakeRepository $makeRepository;
    private TrimRepository $trimRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $hasher = $this->app->make(Hasher::class);
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->makeRepository = $this->app->make(MakeRepository::class);
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->user = User::factory()->create(['password' => $hasher->make('password')]);
        $this->artisan('passport:install')->execute();
        DB::table('oauth_clients')->where(['id' => 2])->update(['user_id' => $this->user->getId()]);
        DB::table('oauth_clients')->select('*')->where(['id' => 2])->first();
    }

    public function testOauthLogin()
    {
        $oauth_client = DB::table('oauth_clients')->select('*')->where(['id' => 2])->first();

        $body = [
            'username' => $this->user->email,
            'password' => 'password',
            'client_id' => 2,
            'client_secret' => $oauth_client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];
        $response = $this->json('POST','/oauth/token',$body,['Accept' => 'application/json']);

        $auth = json_decode( (string) $response->getContent() );

        $body = [
            'username' => $this->user->email,
            'password' => 'password',
            'client_id' => 2,
            'client_secret' => $oauth_client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];

        $trim = $this->trimRepository->get(1);
        $model = $trim->getModel();
        $make = $model->getMake();

        $response = $this->json('GET','/api/make/' . $make->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());
        $this->assertEquals($make->getId(), $jsonObject->id);
        $this->assertEquals($make->getName(), $jsonObject->name);
        $this->assertEquals($make->getContent(), $jsonObject->content);

        $response = $this->json('GET','/api/model/' . $model->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($model->getId(), $jsonObject->id);
        $this->assertEquals($model->getName(), $jsonObject->name);
        $this->assertEquals($model->getContent(), $jsonObject->content);

        $response = $this->json('GET','/api/trim/' . $trim->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($trim->getId(), $jsonObject->id);
        $this->assertEquals($trim->getName(), $jsonObject->name);
    }

    public function testGetModelnames()
    {
        $make = $this->makeRepository->get(1);
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
