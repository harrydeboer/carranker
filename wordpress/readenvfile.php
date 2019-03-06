<?php

declare(strict_types=1);

$phpoptionPath = dirname(__DIR__) . '/vendor/phpoption/phpoption/src/PhpOption/';
require_once($phpoptionPath . '/Option.php' );
require_once($phpoptionPath . '/LazyOption.php' );
require_once($phpoptionPath . '/None.php' );
require_once($phpoptionPath . '/Some.php' );

$dotenvPath = dirname(__DIR__) . '/vendor/vlucas/phpdotenv/src/';
require_once($dotenvPath . '/Environment/Adapter/AdapterInterface.php' );
require_once($dotenvPath . '/Environment/Adapter/ApacheAdapter.php' );
require_once($dotenvPath . '/Environment/Adapter/ArrayAdapter.php' );
require_once($dotenvPath . '/Environment/Adapter/EnvConstAdapter.php' );
require_once($dotenvPath . '/Environment/Adapter/PutenvAdapter.php' );
require_once($dotenvPath . '/Environment/Adapter/ServerConstAdapter.php' );
require_once($dotenvPath . '/Environment/FactoryInterface.php' );
require_once($dotenvPath . '/Environment/VariablesInterface.php' );
require_once($dotenvPath . '/Environment/AbstractVariables.php' );
require_once($dotenvPath . '/Environment/DotenvVariables.php' );
require_once($dotenvPath . '/Environment/DotenvFactory.php' );
require_once($dotenvPath . '/Exception/ExceptionInterface.php' );
require_once($dotenvPath . '/Exception/InvalidFileException.php' );
require_once($dotenvPath . '/Exception/InvalidPathException.php' );
require_once($dotenvPath . '/Exception/ValidationException.php' );
require_once($dotenvPath . 'Dotenv.php' );
require_once($dotenvPath . 'Loader.php' );
require_once($dotenvPath . 'Lines.php' );
require_once($dotenvPath . 'Parser.php' );
require_once($dotenvPath . 'Validator.php' );
$dotenv = Dotenv\Dotenv::create(dirname(__DIR__), '.env');
$dotenv->load();