<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FlushRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flushredis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush redis default database';

    public function handle()
    {
        $redis = new \Redis();
        $redis->connect(env('REDIS_HOST'), (int)env('REDIS_PORT'));
        $redis->auth(env('REDIS_PASSWORD'));
        $redis->select((int) config('database.redis.default.database'));
        $redis->flushDB();
    }
}
