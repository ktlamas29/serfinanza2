<?php
try {
    require_once __DIR__ . "/app/inc/security.php";
    require_once __DIR__ . "/app/inc/server.php";
    require_once __DIR__ . "/app/inc/functions.php";
    require_once __DIR__ . "/app/inc/db.php";

    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
    }

    $db = new ChefDB();
    if (!$debugmode) {
        header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema
    }

    $valid_unique_session = $db->unique_session($_SESSION['idmask']);
    if (!$valid_unique_session) {
        header('Location:' . $exit . '?duplicate_session=1');
        exit;
    }
} catch (Exception $e) {
    header('Location:' . $exit); //si existe la sesión
    die();
}

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si no existe la sesión
    exit;
}

$block = $db->getUserBlock($_SESSION["idmask"], 1, $_SESSION["campaign_blocks"]);
$_SESSION["block"] = $block;
$awards = $db->postPremios();

$codeValidate = false;
if (isset($_POST['code-validate'])) {

    if (!$debugmode) {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf']) {
            unset($_SESSION['csrf_token']);
        } else {
            header('Location:' . $exit);
        }
    }

    $code = test_input2($_POST['code-validate']);
    if ($db->validateCode($code)) {
        $_SESSION['code-validate'] = true;
        $_SESSION['code-validate-attempts'] = 0;
        $codeValidate = true;
    } else {
        $_SESSION['code-validate-attempts'] = $_SESSION['code-validate-attempts'] + 1;
        $_SESSION['code-validate'] = false;
        $_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
    }
}

$budgetLimitReached = $db->budgetLimitReached();

if (!isset($_SESSION['code-validate-attempts'])) {
    $_SESSION['code-validate-attempts'] = 0;
} else {
    if ($_SESSION['code-validate-attempts'] > 3) {
        header('location: /exit');
        die();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include __DIR__ . '/app/partials/metadata.php';
    include __DIR__ . '/partiales/assets-css.php';
    ?>

    <title>Premios | Serfinanza</title>
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
                        <img src="/assets/general/person-awards.png" alt="" class="person-banner wow animated fadeInUpBig">

                    </div>
                    <div class="col-md-6 no-padding title-banner">
                        <div>
                            <h3>Premios </h3>
                            <h6>Premios sin límites</h6>
                            <p>Estos son algunos de los premios que puedes ganar por romper los límites.</p>
                        </div>


                    </div>


                </div>
            </div>
        </section>
        <section class="banner-mobile">
            <img src="/assets/banners/banner-premios.png" alt="">
        </section>
        <div class="">

            <section class="clsGridAdwars">

                <div class="container content-page grid">

                    <div class="text-tp">
                        <p>Aun no sabe cómo redimir premios, consulte la opción <a href="/como-ganar">cómo ganar</a></p>
                    </div>
                    <div class="content-grid">
                        <div id="" class="row">
                            <?php
                            if ($awards) {
                                $is_winner = $db->getCanRedeem($_SESSION['idmask']);
                                $_SESSION['winner'] = $is_winner;
                                foreach ($awards as $award) {
                                    $award_price = $_SESSION['award_price'];
                                    $label_award_price = number_format($award_price, 0, ',', '.');
                            ?>
                                    <div class="col-12  col-md-6">
                                        <div class="item award shadow">

                                            <div class="content-image">
                                                <img src="<?php echo $award['image']; ?>" alt="">
                                            </div>

                                            <div class="description">
                                                <div class="price-name">
                                                    <div class="logo" class="">
                                                        <img src="<?php echo $award['logo_image']; ?>" alt="<?php echo $award['name']; ?>" class="wow animated zoomIn">
                                                    </div>
                                                    <div class="name">
                                                        <span class="price">$<?php echo $label_award_price; ?></span>
                                                        <h4 class="name-award">
                                                            <?php echo $award['name']; ?>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="btn-item">
                                                    <?php
                                                    if ($is_winner) { ?>
                                                        <a href="#" id="<?php echo $award['id']; ?>" data-id="<?php echo $award['id']; ?>" data-description="<?php echo $award['description']; ?>" data-valor="$<?php echo $label_award_price; ?>" data-name="<?php echo $award['name']; ?>" data-logo="<?php echo $award['logo_image']; ?>" data-image="<?php echo $award['image']; ?>" data-toggle="modal" data-target="#contentAward" class="btn btn-main open-modal-confirm">
                                                            Redime tu premio
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="#" class="btn btn-form btn-main disabled">Redimir</a>
                                                    <?php }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                            <?php
                                }
                            }

                            ?>

                        </div>
                    </div>



                </div>
                <div class="content-logo-int">
                    <img src="/assets/logos/logo-serfinanza.png" alt="">
                </div>

            </section>



        </div>

    </div>



    <?php
    include __DIR__ . '/partiales/footer.php';
    include __DIR__ . '/partiales/modal-awards.php';
    include __DIR__ . '/partiales/modalLimitAwards.php';
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

    <script src="js/modalawwards.js"></script>
</body>

</html>