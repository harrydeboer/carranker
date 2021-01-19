<?php

declare( strict_types=1 );

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Redis;

class FlushRedisDb extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'flush:redis-dbs {--testing}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flush Redis db';

	public function handle(): void
	{
		$redis = new Redis();
		$redis->connect(env('REDIS_HOST'), (int) env('REDIS_PORT'));
		$redis->auth(env('REDIS_PASSWORD'));

		/** All databases are flushed when testing. */
		if ($this->option('testing')) {
            $redis->select((int) env('TEST_REDIS_DB_SESSION'));
            $redis->flushDB();
            $redis->select((int) env('TEST_REDIS_DB_CACHE'));
            $redis->flushDB();
            $redis->select((int) env('TEST_REDIS_DB'));
            $redis->flushDB();

            $this->info('Redis dbs flushed!');
        } else {
            $redis->select((int)env('REDIS_DB'));
            $redis->flushDB();

            $this->info('Redis db flushed!');
        }
	}
}
