<?php
require_once __DIR__ . "/app/inc/code-generator/class.coupon.php";
use SubStacks\SMS_Marketing\Coupon;
require_once __DIR__ . "/app/inc/db.php";
$db = new ChefDB();

$code_generator_whitelist = $db->get_whitelist('code-generator');

if (in_array($_SERVER['REMOTE_ADDR'],$code_generator_whitelist)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $separator_double = '===============================================================<br>';
    $separator_simple = '------------------------------------<br>';

    echo $separator_double;
    echo '============================= Inicio ==============================<br>';
    echo $separator_double;

    echo $separator_simple;
    echo 'Insertando idmask<br>';
    echo $separator_simple;
    $CountNewMask = $db->InsertUsersIntoCodesTable();
    echo $separator_simple;
    echo $CountNewMask.' idmask insertados<br>';
    echo $separator_simple;


    $users = $db->getUsersCodeNull();

    echo $separator_simple;
    echo 'Cantidad códigos generados: '.$users->num_rows.'<br>';
    echo $separator_simple;

    $length = 8;
    while ($user = $users->fetch_array()) {
        do {
            $code = Coupon::generate([
                'length' => $length,
                'letters' => true,
                'numbers' => true,
                'symbols' => false,
                'mixed_case' => true,
            ]);
            $insert = $db->insertCode($user['idmask'], $code);
        } while (!$insert);
    }

    echo $separator_double;
    echo '============================= Fin ===============================<br>';
    echo $separator_double;
}else{
    echo 'No está autorizado';
}
