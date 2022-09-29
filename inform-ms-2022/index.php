<?php
require_once "../app/inc/security.php";
require_once "../app/inc/server.php";
require_once "../app/inc/db.php";

$db = new ChefDB();
$setting_key_token_reports = trim($db->getSettingByname('key-reports'));
if (!isset($_GET['key-reports']) || !($_GET['key-reports'] === $setting_key_token_reports)){
  header('Location:' . $exit);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="">
  <title>Document</title>
</head>
<style>
  .content-title-info {
    display: flex;
    padding: 25px 15px;
    align-items: center;
    justify-content: center
  }

  .content-buttons-dashboard {
    width: 60%;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
  }

  .content-buttons-dashboard a {

    width: 45%;
    text-align: center;
    padding: 25px 0;
    font-weight: 700;
    background-color: #005EB8;
    color: #fff;
    margin: 4px;
    -webkit-box-shadow: 0px 0px 17px -5px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: 0px 0px 17px -5px rgba(0, 0, 0, 0.75);
    box-shadow: 0px 0px 17px -5px rgba(0, 0, 0, 0.75);
    transition: 1s ease;
    border-radius: 5px;

  }

  .content-buttons-dashboard a:hover {

    box-shadow: none;
    font-size: 1.2em;

  }
</style>

<body>

  <div class="content-title-info">
    <img src="../assets/logos/banco-bogota-header.svg" alt="">

  </div>


  <section class="tabs-report">
    <div class="content-buttons-dashboard">
      <a href="redenciones.php?key-reports=<?php echo $_GET['key-reports']; ?>">Redención</a>
      <a href="login-r.php?key-reports=<?php echo $_GET['key-reports']; ?>">Login</a>
    </div>
  </section>


  <!-- /Scripts -->



</body>

</html>