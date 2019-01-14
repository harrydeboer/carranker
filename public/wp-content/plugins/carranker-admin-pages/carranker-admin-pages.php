<?php
/*
Plugin Name: Carranker Admin Pages
*/
/* The url is translated to a controller with an action and with an sql query. */
declare(strict_types = 1);

require_once __DIR__ . '/CarrankerAdmin.php';
new \CarrankerAdmin\CarrankerAdmin();
