<?php

declare(strict_types=1);

if (isset($_POST['carrankerAdminAction']) && $_POST['carrankerAdminAction'] == true) {
    $action = $_POST['carrankerAdminAction'];
    unset($_POST['carrankerAdminAction']);
    $request = new stdClass();
    foreach ($_POST as $key => $elem) {
        $request->$key = stripslashes($elem);
    }
} else {
    $action = 'view';
}

$urlArray = explode('/', $_SERVER['REQUEST_URI']);
if ($urlArray[1] === 'wp-admin' && isset($urlArray[2])) {
    $urlRaw = $urlArray[2];
    $urlRaw = str_replace('admin.php?',  '', $urlRaw);
    parse_str($urlRaw, $urlParams);
    $page = $urlParams['page'];


    if ($page === 'make-admin-page') {
        $url = 'make/' . $action;
    } elseif ($page === 'model-admin-page') {
        $url = 'model/' . $action;
    } elseif ($page === 'trim-admin-page') {
        $url = 'trim/' . $action;
    } elseif ($page === 'profanity-admin-page') {
        $url = 'profanity/' . $action;
    } elseif ($page === 'mail-user-admin-page') {
	    $url = 'mailuser/' . $action;
    }

    if (!is_null($url)) {
        require_once __DIR__ . '/controllerCall.php';
    }
}