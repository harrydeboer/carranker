<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Repositories\TrimRepository;
use App\Services\CacheService;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    private $cacheService;
    private $redis;

    public function setUp()
    {
        parent::setUp();

        $this->redis = new \Redis();
        $this->redis->connect(env('REDIS_HOST'), (int)env('REDIS_PORT'));
        $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select((int) config('database.redis.default.database'));

        $this->cacheService = new CacheService($this->redis, 600);
    }

    public function testMakeResponse()
    {
        $title = 'Home';
        $response = $this->cacheService->makeResponse('[*title*]', response(''), 200, '<footer></footer>', $title);

        $this->assertTrue(strpos($response->getContent(), $title) !== false);

        $response = $this->cacheService->makeResponse('[*title*]', response(''), 500, '<footer></footer>', $title);

        $this->assertTrue(strpos($response->getContent(), $title) !== false);

        $response = $this->cacheService->makeResponse('[*title*]', response(''), 404, '<footer></footer>', $title);

        $this->assertTrue(strpos($response->getContent(), $title) !== false);

        $response = $this->cacheService->makeResponse('[*title*]', response(''), 302, '<footer></footer>', $title);

        $this->assertTrue(strpos($response->getContent(), $title) === false);
    }

    public function testCacheFooter()
    {
        $cacheString = 'footerhomepage';
        $this->cacheService->cacheFooter('homepage', session(), 500);

        $this->assertTrue($this->redis->get($cacheString) !== false);

        $cacheString = 'footerhomepagelazy';
        $this->cacheService->cacheFooter('homepage', session(), 200);

        $this->assertTrue($this->redis->get($cacheString) !== false);
    }

    public function testCacheHeader()
    {
        $cacheString = 'headerhomepage';
        $header = $this->cacheService->cacheHeader('homepage', [], false);

        $this->assertTrue($this->redis->get($cacheString) !== false);
        $this->assertTrue(strpos($header, '[*metacsrf*]') === false);

        $cacheString = 'headerauthhomepage';
        $this->cacheService->cacheHeader('homepage', [], true);

        $this->assertTrue($this->redis->get($cacheString) !== false);

        $trimRepository = new TrimRepository();
        $trim = $trimRepository->get(1);
        $make = $trim->getMakename();
        $model = $trim->getModelname();
        $cacheString = 'header' . $make . 'modelpage';
        $header = $this->cacheService->cacheHeader('modelpage', ['make' => $make, 'model' => $model], true);

        $this->assertTrue($this->redis->get($cacheString) !== false);
        $this->assertTrue(strpos($header, 'value="' . $model . '" id="modelnameSession">') !== false);
    }
}