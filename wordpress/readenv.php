<?php

declare(strict_types=1);

require_once __DIR__ . '/Package.php';
$package = new Package();
$package->readPackage('phpoption/phpoption');
$package->readPackage('vlucas/phpdotenv');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();