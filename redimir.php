<?php
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/app/inc/security.php";
if ($debugmode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
if (!$debugmode) {
    // iis sets HTTPS to 'off' for non-SSL requests
    if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
        header('X-Frame-Options: DENY');
    } elseif ($use_sts) {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
        // we are in cleartext at the moment, prevent further execution and output
        die();
    }
}
require_once __DIR__ . "/app/inc/functions.php";

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header("Location: /exit");
    exit;
}
if ($_ENV['DISABLE_REDEMPTIONS'] == 'Y' || !$_SESSION['winner']) {
    header("Location: /premios");
    exit;
}

$block = $_SESSION["block"];

require_once __DIR__ . "/app/inc/db.php";
$db = new ChefDB();
$premioId = test_input2($_GET['premio']);

$valid_unique_session = $db->unique_session($_SESSION['idmask']);
if (!$valid_unique_session) {
    header('Location:' . $exit . '?duplicate_session=1');
    exit;
}

$canRedeem = $db->getCanRedeem($_SESSION["idmask"]);
if (!$canRedeem) {
    header('Location: /exit');
    exit;
}

if (!empty($premioId) && $premioId >= 1) {

    $premioArray = $db->getOneAward($premioId, $_SESSION['award_price'])->fetch_assoc();
    if (!$premioArray) {
        header('Location: /exit');
        exit;
    }

    $price = $_SESSION['award_price'];
    if ($premioArray['value'] > 0) {
        $price = $premioArray['value'];
    }

    //monto total de bonos y datos de notificaciones
    $conteo = $db->postCount();
    $conteo = $conteo->fetch_row();
    $totalBonos = $conteo[0];
    $sumatoriaBonos = $conteo[1] + $price;
    $idNotificaciones = $conteo[2];
    $emails = $conteo[3];
    $concurrencia = $conteo[4];
    $actual = $conteo[5];
    $tope = $conteo[6];
    if ($sumatoriaBonos > $tope) {
        $emailsArray = explode(',', $emails);
        $message = '<h3>Notificación presupuesto</h3><p>La campaña ' . $_SERVER['HTTP_HOST'] . ' llegó al total: ' . number_format($sumatoriaBonos, 0, ',', '.') .
            '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
        $subject = 'Notificación: Presupuesto superado ' . $_SERVER['HTTP_HOST'] . ' [' . number_format($sumatoriaBonos, 0, ',', '.') . ']';
        sendEmail($emailsArray, $message, $subject);
        header("Location: /exit");
        die();
    }

    $voucher_provider = 'QUANTUM';
    if ($premioArray['leal_coins'] == 1) {
        $voucher_provider = 'PUNTOS_LEAL';
    }

#region [Funciones Puntos Leal]
    if ($voucher_provider == 'PUNTOS_LEAL') { // si el premio es un bono de Puntos leal
        $nextCod = $db->getNextCod($premioArray['id'], $price);
        if (empty($nextCod['code'])) {
            $db->postError($_SESSION["idmask"], $premioArray['id'], "1", $block, '', 'error al encontrar bono de puntos leal');
            echo '<script type="text/javascript">alert("Premio no disponible en este momento intenta más tarde");</script>';
            echo '<script type="text/javascript">window.history.back();</script>';
            die();
        }

        $GeneratePDF = false;

        if ($GeneratePDF) {
            // Generate PDF
            require_once __DIR__ . "/app/inc/generateVoucherPDF.php";
            $voucherFile = generateVoucherPDF($premioArray, $nextCod);
        }else{
            // Existing PDF
            $filename = $nextCod['code'].'.pdf';
            $voucherFile = [
                'filename' => $filename,
                'fileurl' => '/voucher_leal/' . $filename
            ];
        }

        $db->saveNextCod($_SESSION['idmask'], $nextCod['id']);
        $db->postError($_SESSION["idmask"], $premioArray['id'], "0", $block, '', 'bono puntos leal redimido: ' . $nextCod['id']);
        $lastRedemption = $db->postGanopremio($premioId, $_SESSION["idmask"], '0', $voucherFile['fileurl'], $price, $block);
        $_SESSION['CurrentRedemption'] = $lastRedemption;
        $db->updateAwardStock($premioArray['id'], $price);
        $swSave = true;

        if ($sumatoriaBonos >= $actual) {
            $valorTotalFormat = number_format($sumatoriaBonos, 0, ',', '.');
            $emailsArray = explode(',', $emails);
            $message = '<h3>Notificación total bonos</h3><p>El total de bonos de la campaña ' . $_SERVER['HTTP_HOST'] . ' paso el valor de: ' . $valorTotalFormat .
                ' La siguiente notificación se realizará después de ' . number_format($actual + $concurrencia, 0, ',', '.') . '</p>' .
                '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
            $subject = 'Notificación total bonos ' . $_SERVER['HTTP_HOST'] . ' [' . $valorTotalFormat . ']';
            sendEmail($emailsArray, $message, $subject);
            $db->updateSiguienteNotificacion();
        }

        if ($swSave) {
            $currentStockRemain = $db->checkStockRemain($premioArray['id'], $price);
            if ($currentStockRemain['subtraction'] == 4 || $currentStockRemain['subtraction'] <= 1) {
                $notifications = $db->postCount()->fetch_assoc();
                $emailsArray = explode(',',$notifications['emails']);
                $message = '<p>Inventario bajo, faltan ' . $currentStockRemain['subtraction'] . ' bonos del comercio ' . $premioArray['name'] . ', por valor: $' . $price . '</p>' .
                    '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
                $subject = 'Campaña ' . $_SERVER['HTTP_HOST'] . ', Inventario bajo, faltan ' . $currentStockRemain['subtraction'] . ' bonos de ' . $currentStockRemain['name'];
                sendEmail($emailsArray, $message, $subject);
            }
            $_SESSION['winner'] = false; // se asigna valor false para prevenir redención doble
            header('location:/redenciones');
            exit;
        } else {
            header("Location: /exit");
            die();
        }

    }
#endregion [Funciones Puntos Leal]

    $QuantumRestUrl = $_ENV['QUANTUM_PREFIX'];
    $headers = [
        "user:" . $_ENV['QUANTUM_USER'],
        "token:" . $_ENV['QUANTUM_PASSWORD'],
        "Content-Type:" . 'application/json'
    ];

    $brand = $premioArray['id_brand_quantum'];
    $idbono = "";
    if ($premioArray['id_product_quantum']) {
        $idbono = $premioArray['id_product_quantum'];
    } else {
        $data = array("brand_id" => $brand);
        $data_string = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/getproducts.json");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $encontrados = "";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($server_output, true);
        $array_uno = $output["response"]["message"];
        foreach ($array_uno as $item) {
            if ($item["pvp"] == $price) {
                $idbono = $item["product_id"];
                break;
            }
        }
    }

    if ($idbono != "" and $brand != "") {
        $idtxt = "";
        $json = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/redeem.json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $postData = [
            'brand_id' => $brand,
            "product_id" => $idbono,
            "user_data" => array(
                'email' => 'chenao@chefcompany.co',
                "name" => "",
                'birthdate' => '',
                "id" => $_SESSION['idmask']
            )
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $server_output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($server_output, true);
        $idtxt = isset($output["response"]["trxid"]) ? $output["response"]["trxid"]:"";
        $json = isset($output["response"]["url"]) ? $output["response"]["url"]:"";
        $responseOutput = isset($output["response"]) ? $output["response"] : null;
        $responseOutputText = json_encode($responseOutput);
        if ($idtxt != "" and $json != "") {
            $lastRedemption = $db->postGanopremio($premioArray['id'], $_SESSION["idmask"], $idtxt, $json, $price, $block);
            $_SESSION['CurrentRedemption'] = $lastRedemption;
           // $_SESSION['totalRedemptions'] += 1;
            $db->postError($_SESSION["idmask"], $brand, $idbono, "0", $responseOutputText, json_encode($postData));
            //enviar notificacion
            if ($sumatoriaBonos >= $actual) {
                $valorTotalFormat = number_format($sumatoriaBonos, 0, ',', '.');
                $emailsArray = explode(',', $emails);
                $message = '<h3>Notificación total bonos</h3><p>El total de bonos de la campaña ' . $_SERVER['HTTP_HOST'] . ' paso el valor de: ' . $valorTotalFormat .
                    ' La siguiente notificación se realizará después de ' . number_format($actual + $concurrencia, 0, ',', '.') . '</p>' .
                    '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
                $subject = 'Notificación total bonos ' . $_SERVER['HTTP_HOST'] . ' [' . $valorTotalFormat . ']';
                sendEmail($emailsArray, $message, $subject);
                $db->updateSiguienteNotificacion();
            }
            $_SESSION['winner'] = false; // se asigna valor false para prevenir redención doble
            header('location:/redenciones');
            exit;
        } else {

            $setting_dev_mails = $db->getSettingByname('dev_mails');
            $emailsArray = explode(',',$setting_dev_mails);
            $message = '<p>Datos del bono con inconvenientes para redimir:  Nombre bono: ' . $premioArray['name'] . ', valor: ' . $price . '</p>' .
                '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
            $subject = 'Bono no disponible en la campaña ' . $_SERVER['HTTP_HOST'];
            sendEmail($emailsArray, $message, $subject);

            $db->postError($_SESSION["idmask"], $brand, $idbono, "1", $responseOutputText, json_encode($postData));
            echo '<script type="text/javascript">alert("Premio no disponible en este momento intenta más tarde");</script>';
            echo '<script type="text/javascript">window.history.back();</script>';
        }
    } else {
        $setting_dev_mails = $db->getSettingByname('dev_mails');
        $emailsArray = explode(',',$setting_dev_mails);
        $message = '<p>Datos del bono con inconvenientes para redimir:  Nombre bono: ' . $premioArray['name'] . ', valor: ' . $price . '</p>' .
            '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
        $subject = 'Error al buscar bono en la campaña ' . $_SERVER['HTTP_HOST'];
        sendEmail($emailsArray, $message, $subject);

        $db->postError($_SESSION["idmask"], $brand, $idbono, "2");
        echo '<script type="text/javascript">alert("Premio no disponible");</script>';
        echo '<script type="text/javascript">window.history.back();</script>';
    }
} else {
    header("Location: /exit");
    die();
}
