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

// User Award
$user_award = (int)$user_goal['award_' . $current_block];

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

    <div class="main-content main-award-content">
        <section class="banner-int-general awards">
            <div class="container">

                <div class="row">
                    <div class="col-12 no-padding title-banner">
                        <p>
                            Premio Especial
                        </p>
                        <span class="blue-direction">¿Aún no sabe como redimir premios?, consulte la opción de <span><a href="/como-ganar">¿Cómo ganar?</a></span> </span>
                    </div>

                </div>
            </div>

        </section>
        <div class="content-page">

            <!--Section steps -->
            <section class="clsMainAward">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 description-main">
                            <h5 class="blue-direction">No pares de usar tu Tarjeta de Crédito Credencial y/o Débito Activa Mastercard Serfinanza para todo</h5>
                            <p>porque si al final de la campaña, eres la persona que más veces cumplió su meta, serás el ganador de este fabuloso premio.</p>
                            <span>Anunciaremos al ganador en un plazo máximo de 15 días hábiles despues de finalizada la campaña.</span>
                            <a href="/como-ganar" class="btn btn-main">¿Cómo ganar?</a>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-main-award">
                                <div class="content-image">
                                    <img src="/assets/bonos/bg-exito.png" alt="">
                                    <div class="logo">
                                        <img src="/assets/bonos/logo-exito.png" alt="">
                                    </div>
                                </div>
                                <div class="description">
                                    <span>Bono de</span>
                                    <span class="price">$15.000.000</span>
                                    <span class="redeem">para redimir en <span>Éxito</span> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!--End section steps -->



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
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'UserID ',
                userId: userId
            });
        });
    </script>
</body>

</html>