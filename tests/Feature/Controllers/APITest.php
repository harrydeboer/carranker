<?php

namespace Tests\Feature\Controllers;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class APITest extends TestCase
{
    private $user;
    private UserRepository $userRepository;
    private MakeRepository $makeRepository;
    private TrimRepository $trimRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->makeRepository = $this->app->make(MakeRepository::class);
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->user = $this->userRepository->get(1);
        $this->artisan('passport:install')->execute();
        DB::table('oauth_clients')->where(['id' => 2])->update(['user_id' => $this->user->getId()]);
        DB::table('oauth_clients')->select('*')->where(['id' => 2])->first();
    }

    public function testOauthLogin()
    {
        $oauth_client = DB::table('oauth_clients')->select('*')->where(['id' => 2])->first();

        $body = [
            'username' => $this->user->user_email,
            'password' => 'password',
            'client_id' => 2,
            'client_secret' => $oauth_client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];
        $response = $this->json('POST','/oauth/token',$body,['Accept' => 'application/json']);

        $auth = json_decode( (string) $response->getContent() );

        $body = [
            'username' => $this->user->user_email,
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
        $jsonArray = json_decode($response->getContent());
        $this->assertEquals($make->getId(), $jsonArray->id);
        $this->assertEquals($make->getName(), $jsonArray->name);
        $this->assertEquals($make->getContent(), $jsonArray->content);

        $response = $this->json('GET','/api/model/' . $model->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonArray = json_decode($response->getContent());

        $this->assertEquals($model->getId(), $jsonArray->id);
        $this->assertEquals($model->getName(), $jsonArray->name);
        $this->assertEquals($model->getContent(), $jsonArray->content);

        $response = $this->json('GET','/api/trim/' . $trim->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonArray = json_decode($response->getContent());

        $this->assertEquals($trim->getId(), $jsonArray->id);
        $this->assertEquals($trim->getName(), $jsonArray->name);
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
