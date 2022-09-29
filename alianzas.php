<?php
require_once "./app/inc/security.php";
require_once "./app/inc/server.php";
require_once "./app/inc/functions.php";
require_once "./app/inc/db.php";

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si existe la sesión
    exit;
}

$db = new ChefDB();

$checkValidDiscount = true;
$setting_time_valid_discount = trim($db->getSettingByname('time_valid_discount')) !== '' ? (int)trim($db->getSettingByname('time_valid_discount')) : false;
if ($setting_time_valid_discount  && time() >= $setting_time_valid_discount) {
    $checkValidDiscount = false;
}
$valid_unique_session = $db->unique_session($_SESSION['idmask']);
if (!$valid_unique_session) {
    header('Location:' . $exit . '?duplicate_session=1');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Descuentos | Serfinanza</title>
</head>

<body>
    <?php include 'partiales/header.php'; ?>
    <div class="main-content">
        <section class="banner-int-general mecanica">
            <div class="figure-bann blue-opa">

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 no-padding img-banner">
                        <img src="/assets/general/person-discounts.png" alt="" class="person-banner wow animated fadeInUpBig">

                    </div>
                    <div class="col-md-6 no-padding title-banner">
                        <div>
                            <h3>Promociones </h3>
                            <h6>Estas son las promociones que te liberan con grandes descuentos</h6>
                            <p>Recuerda que romper los límites con nuestros aliados te ayuda a alcanzar la meta y ganar un premio.</p>
                        </div>


                    </div>


                </div>
            </div>
        </section>
        <section class="banner-mobile">
            <img src="/assets/banners/banner-alianzas.png" alt="">
        </section>
        <div class="">
            <section class="clsAlianzas">
                <div class="container content-page">
                    <div class="content-grid-discounts">
                        <div class="row">
                            <?php if (false) { ?>
                                <div class="col-md-4">
                                    <div class="item award discount shadow">

                                        <div class="content-image">
                                            <img src="/assets/descuentos/bg-mccenter2.png" alt="bg-alianz">
                                            <div class="logo" class="wow animated zoomIn">
                                                <img src="/assets/descuentos/logo-mccenter.png" alt="" class="wow animated zoomIn">
                                            </div>
                                        </div>
                                        <div class="description">
                                            <h3 class="name">Mac Center</h3>
                                            <span class="purple">¡Aprovecha esta oferta limitada, del 4 al 6 de abril!</span>
                                            <p class="desc">
                                                Por la compra de un iphone 11 de 64gb en MAC Center Colombia puedes recibir un bono de $400.000 y un cargador de obsequio
                                            </p>
                                            <a href="https://www.mac-center.com/ " class="btn btn-main">Conoce más</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item award discount shadow">

                                        <div class="content-image">
                                            <img src="/assets/descuentos/bg-mccenter.png" alt="bg-alianz">
                                            <div class="logo" class="wow animated zoomIn">
                                                <img src="/assets/descuentos/logo-mccenter.png" alt="" class="wow animated zoomIn">
                                            </div>
                                        </div>

                                        <div class="description">
                                            <h3 class="name">Mac Center</h3>
                                            <span class="purple">Descuento desde $50.000 hasta $250.000</span>
                                            <p class="desc">
                                                Recibe un bono de descuento en referencias seleccionadas de audio de Mac Center. <br>
                                                <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-maccenter.pdf">Ver términos y condiciones.</a>
                                            </p>

                                            <a href="https://www.mac-center.com/ " class="btn btn-main">Conoce más</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="col-md-4">
                                <div class="item award discount shadow">

                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-presto.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-presto.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">Presto</h3>
                                        <span class="purple">Precios especiales en diferentes combos</span>
                                        <p class="desc">Disfruta de los combos hamburguesa súper con queso, súper pollo a la parrilla y el súper perro con queso.</p>

                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-presto.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <?php if ($checkValidDiscount) { ?>
                                <div class="col-md-4">
                                    <div class="item award discount shadow">

                                        <div class="content-image">
                                            <img src="/assets/descuentos/bg-go-rigo.png" alt="bg-alianz">
                                            <div class="logo" class="wow animated zoomIn">
                                                <img src="/assets/descuentos/logo-go-rigo.png" alt="" class="wow animated zoomIn">
                                            </div>
                                        </div>

                                        <div class="description">
                                            <h3 class="name">Go Rigo Go</h3>
                                            <span class="purple">25% en tienda virtual</span>
                                            <p class="desc">Recibe 25% en tienda virtual en las líneas Urbano y Ciclismo pagando con Mastercard Serfinanza.</p>

                                            <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-gorigogo.pdf" class="btn btn-main">Conoce más</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>


                            <div class="col-md-4">
                                <div class="item award discount shadow">

                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-hbo.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-hbo.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">HBO Max</h3>
                                        <span class="purple">50% de descuento</span>
                                        <p class="desc">¡Obtén un 50% de descuento en tu suscripción a HBO Max al pagar con tu Mastercard!.</p>

                                        <a href="https://elevate.mastercard.com/benefit/product/168573/hbo-max-latam?ac=hbomax-LATAM" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">

                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-despegar.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-despegar.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">Despegar</h3>
                                        <span class="purple">10% de descuento</span>
                                        <p class="desc">Disfruta hasta 10% de descuento en hoteles, autos y actividades.
                                            <a href="https://www.mastercard.com.co/es-co/consumidores/conozca-nuestras-ofertas-y-promociones/despegar/terminos-y-condiciones.html">Ver términos y condiciones.</a>
                                        </p>

                                        <a href="https://mastercard.beneficios.despegar.com.co/login-redirect" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">

                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-luxury.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-luxury.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">Luxury City</h3>
                                        <span class="purple">10% de descuento</span>
                                        <p class="desc">Obtén un 10% de descuento sobre la tarifa regular por noche.
                                            <a href="https://luxurycity.co/Terminos-y-Condiciones-LuxuryCity-MC-WT-VF.pdf">Ver términos y condiciones.</a>
                                        </p>

                                        <a href="https://luxurycity.co/" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="item award discount shadow">

                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-ifood.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-ifood.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">iFood</h3>
                                        <span class="purple">Bono descuento</span>
                                        <p class="desc">
                                            Recibe un bono de $25.000 pesos por compras superiores a $30.000 en iFood
                                        </p>

                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-ifood.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-rappi-drogueria.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-rappi.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h3 class="name">Rappi</h3>
                                        <span class="purple">10% en Droguerías Olímpica</span>
                                        <p class="desc">
                                            Disfruta los miércoles en Olímpica, 40% de descuento en referencias seleccionadas de frutas y verduras.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-rappi-drogueria.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-olimpica.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-olimpica.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">Olimpica</h3>
                                        <span class="purple">40% en frutas y verduras.</span>
                                        <p class="desc">
                                            Disfruta los miércoles en Olímpica, 40% de descuento en referencias seleccionadas de frutas y verduras.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-olimpica.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-gmo.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-gmo.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">GMO Ópticas</h3>
                                        <span class="purple">40% Por la compra de montura + lentes.</span>
                                        <p class="desc">
                                            Recibe descuento de 40% por la compra de montura + lentes y recibe examen visual gratis.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-gmo.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-la-economia.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-la-economia.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">La Economía Droguería</h3>
                                        <span class="purple">20% en medicamentos</span>
                                        <p class="desc">
                                            Todos los jueves disfruta 20% de descuento en medicamentos pagando con tu Tarjeta de Crédito Olímpica.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-la-economia.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-tigo.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-tigo.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">Tigo</h3>
                                        <span class="purple">$300.000 de devolución por compra de equipos</span>
                                        <p class="desc">
                                            Te devolvemos hasta $300.000 por compras de equipos con valor igual o superior a $700.000, diferido a un plazo igual o mayor a 6 meses.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-tigo.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-directvgo.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-directv-go.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">DirectTV</h3>
                                        <span class="purple">Hasta 25% por 6 meses en tu tarifa mensual</span>
                                        <p class="desc">
                                            Disfruta por 6 meses, un descuento de hasta 25% en tu tarifa mensual de DirecTV Go
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-directvgo.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-rappi-frutas.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-rappi.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">Rappi</h3>
                                        <span class="purple">40% en frutas y verduras</span>
                                        <p class="desc">
                                            Miércoles de plaza. Encuentra los miércoles en Rappi 40% de descuento en frutas y verduras.
                                        </p>
                                        <a href="https://serfinanzatepremia.com/assets/descuentos/pdf/tyc-rappi-frutas.pdf" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-mixiaomi.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-mixiaomi.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">Mixiaomi.co</h3>
                                        <span class="purple">20% de descuento</span>
                                        <p class="desc">
                                            Recibe el 20% de descuento en relojes de la marca Amazfit. Ver términos y condiciones.
                                        </p>
                                        <a href="https://mixiaomi.co/product-category/smartwatch/" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="item award discount shadow">
                                    <div class="content-image">
                                        <img src="/assets/descuentos/bg-ucampus.png" alt="bg-alianz">
                                        <div class="logo" class="wow animated zoomIn">
                                            <img src="/assets/descuentos/logo-ucampus.png" alt="" class="wow animated zoomIn">
                                        </div>
                                    </div>
                                    <div class="description">
                                        <h3 class="name">U Campus Academy</h3>
                                        <span class="purple">50% de descuento</span>
                                        <p class="desc">
                                            Recibe el 50% de descuento para cursos y clases en línea de Excel 365 y toda la Suite de Office. Ver términos y condiciones.
                                        </p>
                                        <a href="https://ucampusacademy.com/" class="btn btn-main">Conoce más</a>
                                    </div>
                                </div>
                            </div>
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