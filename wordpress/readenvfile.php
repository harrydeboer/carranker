<?php

declare(strict_types=1);

$phpoptionPath = dirname(__DIR__) . '/vendor/phpoption/phpoption/src/PhpOption/';
require_once($phpoptionPath . '/Option.php' );
require_once($phpoptionPath . '/LazyOption.php' );
require_once($phpoptionPath . '/None.php' );
require_once($phpoptionPath . '/Some.php' );

$dotenvPath = dirname(__DIR__) . '/vendor/vlucas/phpdotenv/src/';
require_once($dotenvPath . '/Exception/ExceptionInterface.php' );
require_once($dotenvPath . '/Exception/InvalidFileException.php' );
require_once($dotenvPath . '/Exception/InvalidPathException.php' );
require_once($dotenvPath . '/Exception/ValidationException.php' );
require_once($dotenvPath . '/Repository/RepositoryInterface.php' );
require_once($dotenvPath . '/Loader/LoaderInterface.php' );
require_once($dotenvPath . '/Loader/Loader.php' );
require_once($dotenvPath . '/Loader/Lines.php' );
require_once($dotenvPath . '/Loader/Parser.php' );
require_once($dotenvPath . '/Loader/Value.php' );
require_once($dotenvPath . '/Repository/Adapter/AvailabilityInterface.php' );
require_once($dotenvPath . '/Repository/Adapter/ReaderInterface.php' );
require_once($dotenvPath . '/Repository/Adapter/WriterInterface.php' );
require_once($dotenvPath . '/Repository/Adapter/ApacheAdapter.php' );
require_once($dotenvPath . '/Repository/Adapter/EnvConstAdapter.php' );
require_once($dotenvPath . '/Repository/Adapter/PutenvAdapter.php' );
require_once($dotenvPath . '/Repository/Adapter/ServerConstAdapter.php' );
require_once($dotenvPath . '/Repository/AbstractRepository.php' );
require_once($dotenvPath . '/Repository/Adapter/ArrayAdapter.php' );
require_once($dotenvPath . '/Repository/AdapterRepository.php' );
require_once($dotenvPath . '/Repository/RepositoryBuilder.php' );
require_once($dotenvPath . '/Regex/Regex.php' );
require_once($dotenvPath . '/Store/StoreInterface.php' );
require_once($dotenvPath . '/Store/File/Paths.php' );
require_once($dotenvPath . '/Store/File/Reader.php' );
require_once($dotenvPath . '/Store/StoreBuilder.php' );
require_once($dotenvPath . '/Store/FileStore.php' );
require_once($dotenvPath . '/Result/Result.php' );
require_once($dotenvPath . '/Result/Success.php' );
require_once($dotenvPath . '/Result/Error.php' );
require_once($dotenvPath . 'Dotenv.php' );
require_once($dotenvPath . 'Validator.php' );

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();