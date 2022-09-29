<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/./db.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
if ($_ENV['PHP_ENV'] == 'production') {

	$db = new ChefDB();

	ini_set('session.cookie_secure', 1);
	ini_set('session.cookie_httponly', 1);
	ini_set('session.use_only_cookies', 1);
	session_start();
	header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	// $debugmode = false;
	$dev_whitelist = $db->get_whitelist('dev');
	$debugmode = (in_array($_SERVER['REMOTE_ADDR'],$dev_whitelist)) ? true : false;
	$use_sts = true;
	if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { //sesión https
		header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
		header('X-Frame-Options: DENY');
	} elseif ($use_sts) {
		header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
		die();
	}
	$site = $_ENV['SITE_URL'];
	$exit = $_ENV['SITE_URL']."/exit";
} else {
	session_start();
	$site = $_ENV['SITE_URL'];
	$exit = $_ENV['SITE_URL'] . "/exit";
	$debugmode = true;
}
