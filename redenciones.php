<?php
require_once "./app/inc/security.php";
require_once "./app/inc/server.php";
require_once "./app/inc/functions.php";
require_once "./app/inc/db.php";

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si no existe la sesión
    exit;
}

$db = new ChefDB();
$idmask = $_SESSION["idmask"];
$campaignBlocks = $_SESSION["campaign_blocks"];

$valid_unique_session = $db->unique_session($_SESSION['idmask']);
if (!$valid_unique_session) {
    header('Location:' . $exit . '?duplicate_session=1');
    exit;
}
// User tracing
$user_tracing = $db->getUserTracing($_SESSION['idmask']);

// Ganador bono pendiente por redimir
$card_1 = '';

// Bono ya redimido

$card_2 = '
<div class="col-12 col-6 col-md-6  redemptions">
    <div class="item award  winner download shadow">
    <div class="content-image">
    <img src="@image@" />
    </div>
   
    <div class="description">
    <div class="price-name">
    <div class="logo">
    <img src="@logo@" alt="@logo_alt@">
    </div>
    <div class="name">
    <h4 class="name-award">
    @name@
   </h4>
    <span class="price">$@price@</span>
    </div>
    
    </div>
    <span class="date">Fecha de redención: @date_text@</span>
    <div class="btn-item">
    <a href="/descargar?block=@block@" id="btn-download-bono" class="btn btn-main open-modal-confirm">Descargar bono</a>
    </div>
    </div>
</div>
</div>';


$awards_show_html = '';
$count_redentions = 0;

for ($i = 1; $i <= $campaignBlocks; $i++) {
    switch ($user_tracing['winner_' . $i]) {
        case '1': // Ganador
            $block = $i;
            $redemption = $db->getOneRedemption($_SESSION["idmask"], $block)->fetch_assoc();
            if (!$redemption) {
                $block_card_1 = $card_1;
                $awards_show_html .= $block_card_1; // Ganador Redención pendiente
            } else {
                $block_card_2 = $card_2;
                $block_card_2 = progressReplaceCard2Values($block_card_2, $redemption);
                $awards_show_html .= $block_card_2; // Ganador Bono ya redimido
                $count_redentions++;
            }
            break;
        default:
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Redenciones | Serfinanza</title>
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
                        <img src="/assets/general/person-redemptions.png" alt="" class="person-banner wow animated fadeInUpBig">

                    </div>
                    <div class="col-md-6 no-padding title-banner">
                        <div>
                            <h3>Mis redenciones </h3>
                            <?php if ($count_redentions == 0) { ?>
                                <h6>Estás cerca de romper los límites, conoce tu progreso y prepárate para ganar</h6>
                            <?php } else { ?>
                                <h6>Estos son los límites que has roto</h6>
                            <?php } ?>

                            <p>Aquí podrás ver los premios que has obtenido hasta ahora.</p>
                        </div>


                    </div>


                </div>
            </div>
        </section>
        <section class="banner-mobile">
            <img src="/assets/banners/banner-redendeciones.png" alt="">
        </section>
        <section class="int-redentions clsGridAdwars redemptions">
            <div class="container content-page">

                <div class="row">
                    <div id="container-redemptions" class="container grid-awards content-awards-redeem">

                        <div class="list-awwards content-grid">
                            <div class="row">
                                <?php
                                if ($count_redentions == 0) { ?>
                                    <div class="col-md-12 cont-notification-redemptions">
                                        <div>
                                            <h2>Aún no tienes redenciones</h2>
                                            <a href="/progreso" class="btn btn-main">Ver mi progreso</a>
                                        </div>

                                    </div>

                                <?php }
                                echo $awards_show_html
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-logo-int">
                <img src="/assets/logos/logo-serfinanza.png" alt="">
            </div>
        </section>
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