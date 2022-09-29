<?php

require_once __DIR__ . "/../app/inc/db.php";
$db = new ChefDB();
$quantum_whitelist = $db->get_whitelist('quantum');

if (in_array($_SERVER['REMOTE_ADDR'],$quantum_whitelist)) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once __DIR__ . "/../app/inc/security.php";
  require_once __DIR__ . "/../app/inc/functions.php";
  require_once __DIR__ . '/../vendor/autoload.php';
} else {
  header('location: ../');
  die();
}
$type = 'product';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

$QuantumRestUrl = $_ENV['QUANTUM_PREFIX'];

$headers = [
  "user:".$_ENV['QUANTUM_USER'],
  "token:".$_ENV['QUANTUM_PASSWORD'],
  "Content-Type:application/json"
];

if (count($_GET) == 0) {
  $vars = "";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/getbrands.json");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $server_output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($server_output, true);
  echo ('<pre>');
  print_r($output["response"]["message"]);
  echo ('</pre>');
}
// verificar productos
elseif ($type === 'brand') {
  $vars = "";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/getproducts.json");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  echo '<h1>brand ' . $brand . '</h1>';
  $postData = [
    'brand_id' => $brand
  ];

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
  $server_output = curl_exec($ch);
  $output = json_decode($server_output, true);
  echo ('<pre>');
  print_r($output["response"]["message"]);
  echo ('</pre>');
  curl_close($ch);
}
// verificar redencion
elseif ($type === 'redeem') {
  $product = isset($_GET['product']) ? $_GET['product'] : '';
  $id = isset($_GET['id']) ? $_GET['id'] : '';
  $price = isset($_GET['price']) ? $_GET['price'] : '';
  if ($product && $id && $price) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/redeem.json");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $postData = [
      'brand_id' => $brand,
      "product_id" => $product,
      "user_data" => array(
        'email' => 'chenao@chefcompany.co',
        "name" => "",
        'birthdate' => '',
        "id" => $id
      )
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $server_output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($server_output, true);
    $idtxt = $output["response"]["trxid"];
    $json = $output["response"]["url"];
    if ($idtxt != "" and $json != "") {
      $db->postError($id, $brand, $product, "0");
      $db->postGanopremio($product, $id, $idtxt, $json, $price, 0);
    }
    echo '<pre>';
    print_r($output["response"]);
    echo '</pre>';
  } else {
    die('parametros no válidos');
  }
}
