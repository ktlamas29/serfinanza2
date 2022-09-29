<?php
require_once "./app/inc/security.php";
require_once "./app/inc/server.php";
require_once "./app/inc/functions.php";
logOut(false);
$addExpire = '';
if (isset($_GET['expires'])) {
    $addExpire = '?expires=1';
}
if (isset($_GET['error_bonus'])) {
    $addExpire = '?error_bonus=1';
}
if (isset($_GET['code_expired'])) {
    $addExpire = '/redimir?code_expired=1';
}
if (isset($_GET['recaptcha_error'])) {
    $addExpire = '?recaptcha_error=1';
}
if (isset($_GET['login_error'])) {
    $addExpire = '?login_error=1';
}
if (isset($_GET['duplicate_session'])) {
    $addExpire = '?duplicate_session=1';
}
header('location: ' . $site . $addExpire);