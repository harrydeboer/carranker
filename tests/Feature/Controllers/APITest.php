<?php

namespace Tests\Feature\Controllers;

use App\Repositories\MakeRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class APITest extends TestCase
{
    private $user;
    private $make;

    public function setUp() {
        parent::setUp();
        $userRepository = new UserRepository();
        $makeRepository = new MakeRepository();
        $this->user = $userRepository->get(1);
        $this->make = $makeRepository->get(1);
        $this->artisan('passport:install')->execute();
        $this->artisan('getfxrate')->execute();
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
        $response = $this->json('GET','/api/make/' . $this->make->getId(),
            $body, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $auth->access_token,
            ]);

        $response->assertStatus(200);
        $jsonArray = json_decode($response->getContent());
        $this->assertEquals($this->make->getId(), $jsonArray->id);
        $this->assertEquals($this->make->getName(), $jsonArray->name);
        $this->assertEquals($this->make->getContent(), $jsonArray->content);
        $this->assertEquals($this->make->getWikiCarMake(), $jsonArray->wiki_car_make);
    }
}