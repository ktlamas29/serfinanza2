<?php
try {
    require_once __DIR__ . "/app/inc/security.php";
    require_once __DIR__ . "/app/inc/server.php";
    require_once __DIR__ . "/app/inc/functions.php";
    require_once __DIR__ . "/app/inc/db.php";
    $db = new ChefDB();
    if (!$debugmode) {
        header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema
    }

    if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
        header('Location:' . $exit); //si no existe la sesión
        exit;
    }

    $valid_unique_session = $db->unique_session($_SESSION['idmask']);
    if (!$valid_unique_session) {
        header('Location:' . $exit . '?duplicate_session=1');
        exit;
    }
} catch (Exception $e) {
    header('Location:' . $exit);
    die();
}

// User goal
$user_goal = $db->getUserGoal($_SESSION['idmask']);

// Current user Block
$current_block = $db->getUserBlock($_SESSION["idmask"], 1, $_SESSION["campaign_blocks"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Cómo ganar | Serfinanza</title>
</head>

<body>
    <?php include 'partiales/header.php'; ?>
    <?php include 'app/partials/tagManagerBody.php'; ?>

    <div class="main-content">
        <section class="banner-int-general mecanica">
            <div class="figure-bann blue-opa">

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 no-padding img-banner">
                        <img src="/assets/general/person-mechanics.png" alt="" class="person-banner wow animated fadeInUpBig">
                    </div>
                    <div class="col-md-6 no-padding title-banner">
                        <div>
                            <h3> ¿Listo para ser libre, rompiendo los límites?</h3>
                            <p>Estos son los pasos que te liberan:</p>
                        </div>

                    </div>
                </div>
            </div>


        </section>
        <section class="banner-mobile">
            <img src="/assets/banners/banner-mecanica.png" alt="">
        </section>
        <div class="">
            <!--Section steps -->

            <section class="clsSteps mecanica">
                <div class="container content-page mechanic-page">

                    <div id="slideMecanica" class="row stepsContent content-slider-steps tpl-mecanica">
                        <div class="col-md-4 ">
                            <div class="step">
                                <div class="content-nume">
                                    <img src="/assets/icons/ico-tarjeta.svg" alt="" class="icon-step">
                                    <img src="/assets/general/num1.png" alt="" class="num">
                                </div>
                                <div class="desc">
                                    <h4 class="title">Rompe los límites comprando con tu tarjeta de crédito donde quieras</h4>
                                    <p>Además de recibir descuentos hasta del 50% sumarás puntos para ganar bonos digitales.</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="step">
                                <div class="content-nume">
                                    <img src="/assets/icons/ico-meta.svg" alt="" class="icon-step">
                                    <img src="/assets/general/num2.png" alt="" class="num">
                                </div>
                                <div class="desc">
                                    <h4 class="title">Alcanza tu meta en compras <br>y números de transacciones.</h4>
                                    <p>Cada vez que rompes los límites, te acercas a tu meta y a tu premio. Para cumplir la meta de # transacciones debes comprar en 2 comercios fuera de Supertiendas y Droguerías Olimpicas S.A</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="step">
                                <div class="content-nume">
                                    <img src="/assets/icons/ico-premio.svg" alt="" class="icon-step">
                                    <img src="/assets/general/num3.png" alt="" class="num">
                                </div>
                                <div class="desc">
                                    <h4 class="title">Redime <br> tu premio</h4>
                                    <p>Una vez alcances tu meta en compras ganarás para ti un código con el que podrás redimir tu premio.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
                    include __DIR__ . '/partiales/section-discounts.php';
                    include __DIR__ . '/partiales/section-alianzas.php';
                    ?>
            </section>
        </div>
    </div>



    <?php
    include __DIR__ . '/partiales/footer.php';

    include __DIR__ . '/partiales/assets-js.php';
    ?>

    <?php
    $idTag = (isset($_SESSION["idmask"])) ? $_SESSION["idmask"] : '';
    ?>

    <script type="text/javascript" nonce="<?php echo $cspNonce[1]; ?>">
        $(function($) {
            var userId = "<?php echo $idTag; ?>";
            window.dataLayer.push({
                event: 'UserID ',
                userId: userId
            });
        });
    </script>
</body>

</html>