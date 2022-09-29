<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();
if ($_ENV['PHP_ENV'] == 'production') {
  $use_sts = true;
  if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
    header('X-Frame-Options: DENY');
  } elseif ($use_sts) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    die();
  }
}
