<?php

#region [Login]
try {
    require_once __DIR__ . "/app/inc/security.php";
    require_once __DIR__ . "/app/inc/server.php";
    require_once __DIR__ . "/app/inc/functions.php";
    require_once __DIR__ . "/app/inc/db.php";

    $db = new ChefDB();
    if (!$debugmode) {

        header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema
    }
    if (isset($_POST['user_name']) && isset($_POST['csrf'])) {

        if (!$debugmode) {
            if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf']) {
                unset($_SESSION['csrf_token']);
            } else {

                header('Location:' . $exit);
            }

            $response = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);
            $valid = recaptcha_validate($response);
            if (!$valid) {

                header('Location:' . $exit . '?recaptcha_error=1');
                exit;
            }
        }

        if (strlen($_POST['user_name']) != 64) {

            header('Location:' . $exit);
            exit;
        }

        $db->postLogin($_POST['user_name']);
        if ($_SESSION['idmask']) {

            header('Location:' . $_SERVER['REQUEST_URI']);
            die();
        }
    } else {
        if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {

            header('Location:' . $exit); //si no existe la sesión
            exit;
        }
    }
} catch (Exception $e) {

    header('Location:' . $exit); //si existe la sesión
    die();
}

$valid_unique_session = $db->unique_session($_SESSION['idmask']);
if (!$valid_unique_session) {

    header('Location:' . $exit . '?duplicate_session=1');
    exit;
}
#endregion [Login]

$idmask = $_SESSION["idmask"];

$campaign_closure = $_ENV['CAMPAIGN_CLOSURE'];

// User goal
$user_goal = $db->getUserGoal($_SESSION['idmask']);

// User tracing
$user_tracing = $db->getUserTracing($_SESSION['idmask']);

// Current user Block
$current_block = $db->getUserBlock($_SESSION["idmask"], 1, $_SESSION["campaign_blocks"]);

// Current Block Amount
$current_block_amount = isset($user_tracing['amount_' . $current_block]) ? (float)$user_tracing['amount_' . $current_block] : 0;

// Tracing Date Update
$tracing_date_update = $user_tracing['date_update'];

// Goal Amount
$goal_amount = (int)$user_goal['goal_amount_' . $current_block];

// User Award
$user_award = (int)$user_goal['award_' . $current_block];

// Percentage Amount current Block
$percentage_amount_current_block = $goal_amount > 0 ? ((int) (($current_block_amount / $goal_amount) * 100)) : 0;

// Percentage Amount current Block bar
$percentage_amount_current_block_bar = $percentage_amount_current_block > 100 ? 100 : $percentage_amount_current_block;

// purchases progreso
$current_purchases = (int)$user_tracing['purchases_' . $current_block];

// purchases meta
$goal_purchases = (int)$user_goal['goal_purchases_' . $current_block];

// Percentage purchases current Block
$percentage_purchases_current_block = $goal_purchases  > 0 ? ((int) (($current_purchases / $goal_purchases) * 100)) : 0;

// Percentage purchases current Block bar
$percentage_purchases_current_block_bar = $percentage_purchases_current_block > 100 ? 100 : $percentage_purchases_current_block;

// Texto rango fecha periodo actual
$current_date_text = $db->getTextInfo('current');

// Texto fecha última actualización
$update_date_text = $db->getTextInfo('update');

// Texto fecha vigencia
$validity_date_text = $db->getTextInfo('validity');

// Es ganador
$is_winner = $db->getIsWinner($idmask, $current_block);

// Puede redimir
$canRedeem = $db->getCanRedeem($idmask);

// Es primer login
$isFirstLogin = $db->isFirstLogin($idmask);

//limit progress bar 
$finalBarLimitAmount = '';
if ($percentage_amount_current_block_bar == 0) {
    $finalBarLimitAmount = '0% - 0px';
} else {
    $finalBarLimitAmount = $percentage_amount_current_block_bar . '% - 32px';
}
$finalBarLimitPurchase = '';
if ($percentage_purchases_current_block_bar == 0) {
    $finalBarLimitPurchase = '0% - 0px';
} else {
    $finalBarLimitPurchase = $percentage_purchases_current_block_bar . '% - 20px';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Progreso | Serfinanza</title>
</head>
<style nonce="<?php echo $cspNonceStyles[0]; ?>">
    .progress-bar.amount {
        width: <?php echo $percentage_amount_current_block_bar . '% !important'; ?>;
    }

    .progress-bar.purchase {
        width: <?php echo  $percentage_purchases_current_block_bar . '% !important'; ?>;
    }

    .cont-progress-gen .bar span.amount-ind {
        left: calc(<?php echo $finalBarLimitAmount ?>) !important;
    }

    .cont-progress-gen .bar span.purchase-ind {
        left: calc(<?php echo  $finalBarLimitPurchase;  ?>) !important;
    }
</style>

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
                        <img src="/assets/general/person-progress.png" alt="" class="person-banner wow animated fadeInUpBig">

                    </div>
                    <div class="col-md-6 no-padding title-banner">
                        <h3> Cada día rompes más los límites </h3>
                    </div>


                </div>
            </div>


        </section>
        <section class="banner-mobile">
            <img src="/assets/banners/banner-progreso.png" alt="">
        </section>
        <div class="">

            <section class="clsProgressGeneral">
                <div class="container content-page int-page">

                    <section class="content-section-bar">
                        <div class="container sect-int">
                            <div class="row">
                                <div class="col-md-6 desc-progress">
                                    <h3 class="title-section">
                                        Mi progreso
                                    </h3>
                                    <p>Todas tus compras te acercan a la meta. <span> Estás muy cerca de ganar tu premio.</span></p>
                                    <p><span>Completa tus metas para redimir tu premio</span></p>
                                    <?php $btnEnabledClass = ($canRedeem) ? '' : 'disabled'; ?>
                                    <a href="/premios" class="btn general desk <?php echo $btnEnabledClass; ?>">Redime tu premio</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="cont-progress-gen">
                                        <div class="resume">
                                            <p>Monto en compras</p>
                                            <span>Meta: <?php echo '$' . number_format($goal_amount, 0, ',', '.'); ?> </span>
                                        </div>
                                        <div class="bar">
                                            <span class="amount-ind"><?php echo $percentage_amount_current_block_bar . '%';  ?></span>
                                            <div class="progress">
                                                <div class="progress-bar amount" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <?php
                                        if ($goal_purchases != 0) { ?>
                                            <div class="cont-purchases">
                                                <div class="resume">
                                                    <p># de transacciones fuera de comercios Supertiendas y Grupo Olímpica</p>
                                                    <span>Meta: <?php echo $goal_purchases; ?> </span>
                                                </div>
                                                <div class="bar">

                                                    <div class="progress">
                                                        <span class="purchase-ind"><?php echo $percentage_purchases_current_block_bar . '%';  ?></span>
                                                        <div class="progress-bar purchase" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php }
                                        ?>
                                        <span class="update">
                                            Actualizado al <?php echo $tracing_date_update; ?>
                                        </span>

                                    </div>
                                    <?php $btnEnabledClass = ($canRedeem) ? '' : 'disabled'; ?>
                                    <a href="/premios" class="btn general mobile <?php echo $btnEnabledClass; ?>">Redime tu premio</a>
                                </div>

                            </div>
                        </div>

                    </section>
                    <?php
                    include __DIR__ . '/partiales/section-discounts.php';
                    include __DIR__ . '/partiales/section-alianzas.php';
                    ?>
                </div>
                <div class="content-logo-int">
                    <img src="/assets/logos/logo-serfinanza.png" alt="">
                </div>
            </section>
        </div>
        <?php
        include __DIR__ . '/partiales/footer.php';
        include __DIR__ . '/partiales/assets-js.php';
        ?>
        <script src="/js/progress.js">
        </script>
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