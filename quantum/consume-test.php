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
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
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
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  echo '<h1>brand ' . $brand . '</h1>';
  $postData = [
    'brand_id' => $brand
  ];
  // $p = "80";
  // $price = $p . "000";
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
  die('redimir');
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
      $db->postGanopremio($product, $id, $idtxt, $json, $price, 1);
    }
    echo '<pre>';
    print_r($output["response"]);
    echo '</pre>';

  } else {
    die('parametros no válidos');
  }
} elseif ($type === 'pre-redeem') {
  $product = isset($_GET['product']) ? $_GET['product'] : '';
  $id = 'testChef';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/preredeem.json");
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
  echo '<pre>';
  print_r($output["response"]);
  echo '</pre>';
} elseif ($type === 'table') {
  require_once __DIR__ . "/../app/inc/functionsQuantum.php";
  $quantum = new consumeQuantum();
  $brands = $quantum->getBrands();
  $list = [];
  foreach ($brands as $brand) {
    $products = $quantum->getProductsByBrand($brand['brand_id']);
    if ($products) {
      foreach ($products as $product) {
        $list[] = array_merge(['logo' => $brand['logo'], 'location' => $brand['location']], $product);
      }
    }
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="minimum-scale=1, initial-scale=1, width=device-width" />
    <script
      src="https://code.jquery.com/jquery-1.12.3.js"
      integrity="sha384-i76OvrFZfIhyIh2rh+77S7CFLBr/Air9uEP0nQoeDp4Haua0dbw4epkvQQCzA4uf"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"
      integrity="sha384-89aj/hOsfOyfD0Ll+7f2dobA15hDyiNb8m1dJ+rJuqgrGR+PVqNU8pybx4pbF3Cc"
      crossorigin="anonymous"></script>
    <script
      src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"
      integrity="sha384-Glu7J+J/v1zgPOfuwZpvF0NjviqX4WL9Q9VrexEg08sHWdZDl/oTCVnGhMM4h5ry"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"
      integrity="sha384-wSYm3f5KwqkbUyg1t+MTmmR9bfnkC0/8c5jXFDqjkOl9nAg5H0eS2Tx2Ca7dB8hk"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"
      integrity="sha384-v9EFJbsxLXyYar8TvBV8zu5USBoaOC+ZB57GzCmQiWfgDIjS+wANZMP5gjwMLwGv"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"
      integrity="sha384-HUHsYVOhSyHyZRTWv8zkbKVk7Xmg12CCNfKEUJ7cSuW/22Lz3BITd3Om6QeiXICb"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"
      integrity="sha384-HUHsYVOhSyHyZRTWv8zkbKVk7Xmg12CCNfKEUJ7cSuW/22Lz3BITd3Om6QeiXICb"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"
      integrity="sha384-iQbqrrmrX47bVoYPufJoQUIvdMNZ9WwKie1bMaAjI4lnlw10H8nF90dzwHbH5BXL"
      crossorigin="anonymous">
    </script>
    <script
      src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"
      integrity="sha384-XB54OBl3rOOjrhqlr+qwWqLv0GRnzVGFYKHPwdJnah4TyB7vQuYckQSsxQxGOA8d"
      crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Productos Quantum</title>
  </head>

  <body>
    <div class="container">

      <p class="d-none" id="descriptTable">Table dev</p>
      <table id="MyTable" aria-label="Admin dev" class="display" cellspacing="0" width="100%" aria-describedby="descriptTable">
        <thead>
          <tr>
            <th id="name">Nombre producto</th>
            <th id="product_id">Producto id</th>
            <th id="description">Descripcion</th>
            <th id="mark">Marca</th>
            <th id="mark_id">Marca id</th>
            <th id="location">Locacion</th>
            <th id="value">Valor</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th id="nameS">Nombre producto</th>
          </tr>
        </tfoot>
        <tbody>
          <?php foreach ($list as $item) { ?>
            <tr>
              <td scope="name"><?php echo $item['name'] ?></td>
              <td scope="product_id"><?php echo $item['product_id'] ?></td>
              <td scope="description"><?php echo $item['description'] ?></td>
              <td scope="mark"><?php echo $item['brand_name'] ?></td>
              <td scope="mark_id"><?php echo $item['brand_id'] ?></td>
              <td scope="location"><?php echo ($item['location'] == 1) ? 'Si' : 'No'; ?></td>
              <td scope="value">$<?php echo number_format($item['pvp'], 2, ',', '.') ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#MyTable').DataTable({
          initComplete: function() {
            this.api().columns().every(function() {
              var column = this;
              var select = $('<select><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );
                  //to select and search from grid  
                  column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
                });

              column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
              });
            });
          },
          "pageLength": 50,
          "language": {
            "search": "Buscar:",
            "info": "Mostrando _START_ de _END_ de _TOTAL_ registros",
            "paginate": {
              "first": "Primero",
              "last": "Ultimo",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "lengthMenu": "Mostrando _MENU_ registros",
          },
            dom: 'Bfrtip',
            buttons: ['excel','pageLength']
        });
      });
    </script>

  </body>

  </html>
<?php
}
