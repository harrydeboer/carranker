<?php

declare(strict_types=1);

require_once __DIR__ . '/Package.php';
require_once dirname(__DIR__) . '/vendor/laravel/framework/src/Illuminate/Support/Env.php';

$package = new Package();
$package->readPackage('phpoption/phpoption');
$package->readPackage('graham-campbell/result-type');
$package->readPackage('vlucas/phpdotenv');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
