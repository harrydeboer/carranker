<?php

declare( strict_types=1 );

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FlushRedisDB extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'flushredisdb';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flush Redis db';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle(): void
	{
		$this->redis = new \Redis();
		$this->redis->connect(getenv('REDIS_HOST'), (int) getenv('REDIS_PORT'));
		$this->redis->auth(getenv('REDIS_PASSWORD'));
		$this->redis->select((int) getenv('REDIS_DB'));
		$this->redis->flushDB();

		$this->info('Redis db flushed!');
	}
}