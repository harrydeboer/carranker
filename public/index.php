<?php

declare(strict_types=1);

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

$dotenvPath = dirname(__DIR__) . '/vendor/vlucas/phpdotenv/src/';
require_once($dotenvPath . 'Dotenv.php' );
require_once($dotenvPath . 'Loader.php' );
require_once($dotenvPath . 'Validator.php' );
$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$redis = new Redis();
$redis->connect(getenv('REDIS_HOST'), (int) getenv('REDIS_PORT'));
$redis->auth(getenv('REDIS_PASSWORD'));
$redis->select(getenv('APP_ENV') === 'testing' ? (int) getenv('TEST_REDIS_DB') : (int) getenv('REDIS_DB'));

$redisSession = new Redis();
$redisSession->connect(getenv('REDIS_HOST'), (int)getenv('REDIS_PORT'));
$redisSession->auth(getenv('REDIS_PASSWORD'));
$redisSession->select(getenv('APP_ENV') === 'testing' ? (int)getenv('TEST_REDIS_DB_SESSION') : (int)getenv('REDIS_DB_SESSION'));

$illuminatePath = dirname(__DIR__) . '/vendor/laravel/framework/src/Illuminate';
require_once($illuminatePath . '/Contracts/Encryption/DecryptException.php');
require_once($illuminatePath . '/Contracts/Encryption/EncryptException.php');
require_once($illuminatePath . '/Contracts/Encryption/Encrypter.php');
require_once($illuminatePath . '/Encryption/Encrypter.php');

$encrypter = new \Illuminate\Encryption\Encrypter(base64_decode(substr(getenv('APP_KEY'), 7)), getenv('APP_CIPHER'));

$url = $_SERVER['REQUEST_URI'];
$cacheExpire = getenv('APP_ENV') === 'local' ? 120 : 3600;
session_start();

if ($url === '/' && $redis->get($url) !== false && isset($_SESSION['lazyLoad'])) {

    $isLazyLoad = false;
    if (isset($_COOKIE['laravel_session'])) {
        $result = $encrypter->decrypt($_COOKIE['laravel_session'], false);
        $redisData = $redisSession->get('laravel:' . $result);
        $sessionString = unserialize($redisData, ['allowed_classes' => true]);
        $sessionObject = unserialize($sessionString);

        if (!isset($sessionObject['aspects'])) {
            echo $redis->get($url);
        } else {
            goto nocache;
        }
    } else {
        echo $redis->get($url);
    }

} else if ($url === '/' && $redis->get($url . 'lazy') !== false && !isset($_SESSION['lazyLoad'])) {

    $_SESSION['lazyLoad'] = false;
    $isLazyLoad = true;
    echo $redis->get($url . 'lazy');

} else {

    nocache:

    /*
    |--------------------------------------------------------------------------
    | Register The Auto Loader
    |--------------------------------------------------------------------------
    |
    | Composer provides a convenient, automatically generated class loader for
    | our application. We just need to utilize it! We'll simply require it
    | into the script here so that we don't have to worry about manual
    | loading any of our classes later on. It feels great to relax.
    |
    */

    require __DIR__ . '/../vendor/autoload.php';

    /*
    |--------------------------------------------------------------------------
    | Turn On The Lights
    |--------------------------------------------------------------------------
    |
    | We need to illuminate PHP development, so let us turn on the lights.
    | This bootstraps the framework and gets it ready for use, then it
    | will load up this application so that we can run it and send
    | the responses back to the browser and delight our users.
    |
    */

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    /*
    |--------------------------------------------------------------------------
    | Run The Application
    |--------------------------------------------------------------------------
    |
    | Once we have the application, we can handle the incoming request
    | through the kernel, and send the associated response back to
    | the client's browser allowing them to enjoy the creative
    | and wonderful application we have prepared for them.
    |
    */

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );

    if ($url === '/' && isset($_SESSION['lazyLoad'])) {
        if (!isset($sessionObject['aspects'])) {
            $redis->set($url, $response->getContent(), $cacheExpire);
        }
    } else if ($url === '/' && !isset($_SESSION['lazyLoad'])) {
        $redis->set($url . 'lazy', $response->getContent(), $cacheExpire);
    }
    $response->send();

    $kernel->terminate($request, $response);
}
