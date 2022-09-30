<?php
require_once __DIR__ . '/app/inc/security.php';
require_once __DIR__ . "/app/inc/db.php";
$db = new ChefDB();
$setting_time_end_campaing = trim($db->getSettingByname('time_end_campaing')) !== '' ? (int)trim($db->getSettingByname('time_end_campaing')) : false;
// if ($setting_time_end_campaing && time() >= $setting_time_end_campaing) {

//     require_once __DIR__ . "/partiales/modal-end-campaign.php";
// }

if (isset($_SESSION["idmask"])) {
    $exit = $_ENV['SITE_URL'] . "/exit";
    header('Location:' . $exit); //si existe la sesión
    exit;
}

if (isset($_GET['utmweb']) && strlen($_GET['utmweb']) === 64) {
    $login = $db->postLoginSHA($_GET['utmweb']);
    if ($login != "0") {
        header('Location: ./progreso'); //si existe la sesión
        exit;
    }
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
    <title> Login | Serfinanza</title>
</head>

<body>

    <?php include 'app/partials/tagManagerBody.php'; ?>
    <header class="int mobile mobile-head">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="logo-login">
                        <img src="/assets/logos/logo-login.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="main-content-login">
        <section class="main-content bg-tpl-init login-int">
            <div class="container">
                <div class="row ">
                    <div class="col-12 col-lg-6 ">
                        <div>
                            <img src="assets/banners/imagen-principal.png" alt="">
                        </div>
                        <div class="containe-fluid">
                            <div class="row">
                                <div class="col-3 pr-0">
                                    <div class="icon-titular text-right ">
                                        <img src="assets/icon/iconos-titular.svg" alt="">
                                    </div>
                                </div>

                                <div class="col-9">
                                    <div class="text-login">
                                        <p class="">
                                            <div class="row">
                                                <div class="col-6"><strong class="color-blue font-49 font-40-r ahora">Ahora</strong></div>
                                                <div class="col-6">
                                                    <strong class="color-blue font-49 font-49-r ahora"> rompe</strong>

                                                </div>
                                            </div>
                                        </p>
                                        <p class="font-56 todos"><strong>todos <span class="mt-5 color-red font-62">los</span></strong> </p>
                                        <p class="font-87 limites"><strong>límites</strong></p>

                                        <p class="font-20 comprando">
                                            <strong>comprando con tu <span class="color-red">Tarjeta <br class="d-lg-block d-none"> Olímpica Mastercard</span> donde quieras.</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>

                    <!-- Col formulario -->
                    <div class="col-12 col-lg-5 d-center mt-5 ">
                        <div class="mt-5">
                            <br>
                            <h3 class="font-20  fontsfm-noto_black">
                                <strong>Puedes usarla para el mercado, ¡y también para Netflix!</strong>
                            </h3>
                            <p class="font-16">
                                Úsala para todo lo que te gusta, atrévete a romper todos los límites y gana bonos de regalo de tus comercios favoritos.
                            </p>
                            <br>
                        </div>

                        <div class="content-col-form ">
                            <div>

                                <div class="content-form">
                                    <form id="form_login" class="form-login" action="/progreso" method="POST" autocomplete="off">
                                        <div class="first-form">
                                            <div class="form-group">
                                                <label class="color-blue font-16 " for="">
                                                    <strong class="fontsfm-noto_black">
                                                        Ingresa con tu código
                                                    </strong>
                                                </label>
                                                <img class="mx-2" src="assets/icon/info.svg" alt="">
                                                <div class="cont-inp">
                                                    <input type="text" class="form-control input-user" maxlength="12" name="user_s" required id="" placeholder="Digita tu código aquí">
                                                </div>

                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group accept-tyc d-flex">
                                            <div class="invalid-feedback">
                                            </div>
                                            <input type="checkbox" name="tyc" class="styled-checkbox m-1" id="tyc" value="s">
                                            <label class="bulletTyc " for="tyc"></label>
                                            <p class=" color-gray-1 font-14 ">
                                                He leído y acepto los <a class="font-bold color-blue" href="/terminos-condiciones"><strong>términos y condiciones</strong></a>
                                            </p>


                                        </div>

                                        <div class="invalid-feedback <?php echo (isset($_GET['login_error']) || isset($_GET['recaptcha_error'])) ? 'active' : ''; ?>">
                                            <?php
                                            if (isset($_GET['login_error'])) {
                                                echo 'Verifica tus datos';
                                            } else {
                                                echo 'Error con el recaptcha, intenta nuevamente.';
                                            }
                                            ?>
                                        </div>
                                        <div class="actions-block mt-4">
                                            <a href="#" class="send-form-login btn-main">Ingresar</a>
                                        </div>
                                        <input type="hidden" value="" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <input type="hidden" value="" name="user_name">
                                        <input type="hidden" value="" name="user_password">
                                        <input type="hidden" value="<?php echo $_SESSION["csrf_token"]; ?>" name="csrf">
                                    </form>

                                </div>

                                <!-- <div class="links login">
                                    <a href="/tyc.php">Términos y condiciones</a>
                                    <a href="/preguntas-frecuentes">Preguntas Frecuentes</a>
                                </div> -->
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
    include 'partiales/footer.php';
    ?>
    <?php include __DIR__ . '/partiales/assets-js.php'; ?>
    <script src="/js/login.js"></script>
    <?php
    if (!$debugmode) {
        include 'app/partials/recaptcha_login.php';
    }
    ?>
    <?php
    if ($login_error) {
    ?>
        <script text="text/javascript" nonce="<?php echo $cspNonce[2]; ?>">
            $(function($) {
                window.dataLayer.push({
                    'Event': 'login_error',
                });
            });
        </script>
    <?php
    }
    ?>


</body>

</html>