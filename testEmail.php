<?php
require_once __DIR__ . "/app/inc/db.php";
$dev_whitelist = $db->get_whitelist('dev');

if (!in_array($_SERVER['REMOTE_ADDR'],$dev_whitelist)) {
    die();
}
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



echo date('Y-m-d H:i:s');

die();



require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/app/inc/functions.php";


if (in_array($_SERVER['REMOTE_ADDR'],$dev_whitelist)) {
    die('es mi pc');
}


            
die();



$message = '<h3>Notificación total bonos</h3><p>El total de bonos de la campaña ' . $_SERVER['HTTP_HOST'] . ' paso el valor de: prueba' .
    ' La siguiente notificación se realizará después de prueba,000' . '</p>' .
    '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
    $setting_dev_mails = $db->getSettingByname('dev_mails');
    $emailsArray = explode(',',$setting_dev_mails);
sendEmail($emailsArray, $message, 'Notificación total bonos ' . $_SERVER['HTTP_HOST'] . ' [prueba]');