<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| The default connection is never used, except when the config cache is not filled. To prevent the real mysql
	| database to be wiped when running unit tests, the default is set to sqlite_testing to be safe.
	|
	*/

	'default' => env('DB_CONNECTION'),

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => [

		'sqlite_testing' => [
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => '',
		],

		'mysql' => [
			'driver' => 'mysql',
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE'),
			'username' => env('DB_USERNAME'),
			'password' => env('DB_PASSWORD'),
			'unix_socket' => env('DB_SOCKET'),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix' => '',
			'strict' => true,
			'engine' => null,
			'modes'  => env("APP_ENV") === 'local' || env("APP_ENV") === 'testing' ?
				['ONLY_FULL_GROUP_BY',
					'STRICT_TRANS_TABLES',
					'NO_ZERO_IN_DATE',
					'NO_ZERO_DATE',
					'ERROR_FOR_DIVISION_BY_ZERO',
					'NO_ENGINE_SUBSTITUTION',] : null,
			'options' => env("APP_ENV") === 'local' || env("APP_ENV") === 'testing' ? null :
				[
					PDO::MYSQL_ATTR_SSL_CA => '/var/lib/mysql/ca.pem',
					PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
				],
		],

		'test_mysql' => [
			'driver' => 'mysql',
			'host' => env('DB_HOST'),
			'port' => env('DB_PORT'),
			'database' => env('TEST_DATABASE'),
			'username' => env('DB_USERNAME'),
			'password' => env('DB_PASSWORD'),
			'unix_socket' => env('DB_SOCKET'),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix' => '',
			'strict' => true,
			'engine' => null,
			'modes'  =>
				[
					'ONLY_FULL_GROUP_BY',
					'STRICT_TRANS_TABLES',
					'NO_ZERO_IN_DATE',
					'NO_ZERO_DATE',
					'ERROR_FOR_DIVISION_BY_ZERO',
					'NO_ENGINE_SUBSTITUTION',
				],
		],

		'pgsql' => [
			'driver' => 'pgsql',
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '5432'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'charset' => 'utf8',
			'prefix' => '',
			'schema' => 'public',
			'sslmode' => 'prefer',
		],

		'sqlsrv' => [
			'driver' => 'sqlsrv',
			'host' => env('DB_HOST', 'localhost'),
			'port' => env('DB_PORT', '1433'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'charset' => 'utf8',
			'prefix' => '',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [

		'client' => 'phpredis',

		'default' => [
			'host'     => env('REDIS_HOST'),
			'password' => env('REDIS_PASSWORD'),
			'port'     => env('REDIS_PORT'),
			'database' => env('APP_ENV') === 'testing' ? env('TEST_REDIS_DB') : env('REDIS_DB'),
		],

		'session' => [
			'host' => env('REDIS_HOST'),
			'password' => env('REDIS_PASSWORD'),
			'port' => env('REDIS_PORT'),
			'database' => env('APP_ENV') === 'testing' ? env('TEST_REDIS_DB_SESSION') : env('REDIS_DB_SESSION'),
		],

		'cache' => [
			'host'     => env('REDIS_HOST'),
			'password' => env('REDIS_PASSWORD'),
			'port'     => env('REDIS_PORT'),
			'database' => env('APP_ENV') === 'testing' ? env('TEST_REDIS_DB_CACHE') : env('REDIS_DB_CACHE'),
		],
	],
];
