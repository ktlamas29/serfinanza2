<?php

require_once __DIR__ . "/app/inc/db.php";
$db = new ChefDB();
$quantum_whitelist = $db->get_whitelist('quantum');

if (!in_array($_SERVER['REMOTE_ADDR'],$quantum_whitelist)) {
    die();
}

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$QuantumRestUrl = 'wsrest'; //urltest

$headers = [
    "user:".$_ENV['QUANTUM_USER'],
    "token:".$_ENV['QUANTUM_PASSWORD'],
    "Content-Type:application/json"
  ];


$price = $_GET['price'];
$brand = $_GET['brand'];

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
echo '<pre>';
print_r($output["response"]);
echo '</pre>';

echo json_encode($output["response"]);



foreach ($array_uno as $item) {
    if ($item["pvp"] == $price) {
        $idbono = $item["product_id"];
        break;
    }
}


echo '<pre>';
print_r($idbono);
echo '</pre>';
echo '<pre>';
print_r($brand);
echo '</pre>';
die();
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
            "id" => 'testChef'
        )
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $server_output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($server_output, true);
    $idtxt = (isset($output["response"]["trxid"])) ? $output["response"]["trxid"] : '';
    $json = (isset($output["response"]["url"])) ? $output["response"]["url"] : '';

    echo '<pre>';
    print_r($output["response"]);
    echo '</pre>';

}
