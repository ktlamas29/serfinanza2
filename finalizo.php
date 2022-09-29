<?php
require_once __DIR__ . '/app/inc/security.php';

if (isset($_SESSION["idmask"])) {
    $exit = $_ENV['SITE_URL'] . "/exit";
    header('Location:' . $exit); //si existe la sesión
    exit;
}

$_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
$recaptcha_error = isset($_GET['recaptcha_error']) ? true : false;
$login_error = isset($_GET['login_error']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Banco de Bogotá</title>
</head>

<style nonce="<?php echo $cspNonceStyles[0]; ?>">
    .second-form {
        display: <?php echo 'none'; ?>;
    }
</style>

<body>
    <?php include 'app/partials/tagManagerBody.php';
    ?>


    <div class="main-content bg-tpl-init">

        <div class="header-init">
            <div class="container">
                <div class="main-logo">
                    <a>
                        <img src="assets/logos/logo-banco-bogota.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="container">

            <div class="row cont-log">
                <div class="col-md-6">
                    <div class="content-title-login">
                        <div class="yellow-word">
                            <span>VIVE TUS</span> <br>
                            <span>COMPRAS Y GANA </span><br>
                            <span>¡HA FINALIZADO!</span>
                        </div>
                        <p>
                            Sigue usando tus <span>Tarjetas de Crédito Mastercard <br> del Banco de Bogotá</span> y disfruta de sus beneficios.
                        </p>

                        <p class="thx-yellow">
                            ¡Gracias por participar!
                        </p>
                    </div>



                </div>
                <div class="col-md-6"></div>
            </div>
        </div>

        <img src="assets/logos/vigilado.svg" alt="" class="vigilado login">

    </div>

    <?php include __DIR__ . '/partiales/footer.php';  ?>

    <?php include __DIR__ . '/partiales/assets-js.php'; ?>
    <?php
    if ($debugmode) { ?>
        <script src="/js/index-debug.js"></script>
    <?php } else { ?>
        <script src="https://www.google.com/recaptcha/api.js?render=6Lf6HAAaAAAAAPCJp2-Bv5ljpsxz9yef-3LX9gLs"></script>
        <script src="/js/index.js"></script>
    <?php } ?>
</body>

</html>