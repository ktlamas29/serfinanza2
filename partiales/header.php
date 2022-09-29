<?php
$isLogin = false;
if (isset($_SESSION["idmask"]) || !empty($_SESSION["idmask"])) {
    $isLogin = true;
}
$anchoActive = true;
if ($_SERVER['SCRIPT_NAME'] != '/index.php') {
    $anchoActive = false;
}
?>
<header class="int mobile mobile-head">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="logo-login">
                    <img src="/assets/logos/logo-login.png" alt="">
                </div>
            </div>
        </div>
    </div>
</header>
<header class="int desk">
    <div class="container">
        <div class="row cont-header">
            <div class="col-12 col-md-4">
                <div class="logo-header">
                    <a href="/progreso">
                        <img src="/assets/logos/logo-serfinanza.png" alt="" />
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-8 main-menu">
                <nav class="nav-header">
                    <ul class="d-flex">
                        <?php if (isset($_SESSION['idmask']) && $_SESSION['idmask'] != "") { ?>
                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? 'active' : ''; ?>">
                                <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? 'link-nav_active' : '' ?> " href="/progreso">
                                    <img src="/assets/icons/menu/menu-progreso.svg" alt="">
                                    Progreso</a>
                            </li>
                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'link-nav_active' : '' ?>" href="/promociones">
                                    <img src="/assets/icons/menu/menu-como-ganar.svg" alt="">
                                    Promociones</a>
                            </li>
                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? 'active' : ''; ?>">
                                <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? 'link-nav_active' : '' ?>" href="/premios">
                                    <img src="/assets/icons/menu/menu-premios.svg" alt="">
                                    Premios</a>
                            </li>

                            <li class="item-menu content-options <?php echo ($_SERVER['PHP_SELF'] == '/redenciones.php') ? 'active' : ''; ?>">
                                <a id="show-submenu" class="link-nav navMore <?php echo ($_SERVER['PHP_SELF'] == '/redenciones.php') ? 'link-nav_active' : '' ?>" href="">
                                    Otras opciones
                                    <img src="/assets/icons/ico-down-menu.svg" alt="">
                                </a>
                                <div id="submenu" class="submenu-menu-desk ">
                                    <div class="container">
                                        <ul>
                                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/redenciones.php') ? 'active' : ''; ?>">
                                                <a class="link-nav navAlianzas <?php echo ($_SERVER['PHP_SELF'] == '/redenciones.php') ? 'link-nav_active' : '' ?>" href="/redenciones">
                                                    <img src="/assets/icons/menu/ico-redenciones-menu.svg" alt="">
                                                    Mis redenciones</a>
                                            </li>
                                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/como-ganar.php') ? 'active' : ''; ?>">
                                                <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/como-ganar.php') ? 'link-nav_active' : '' ?>" href="/como-ganar">
                                                    <img src="/assets/icons/menu/ico-meta.svg" alt="">
                                                    ¿Cómo ganar?</a>
                                            </li>
                                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                                <a class="link-nav navAlianzas <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'link-nav_active' : '' ?>" href="/terminos-condiciones">
                                                    <img src="/assets/icons/menu/ico-terminos-menu.svg" alt="">
                                                    Términos y condiciones</a>
                                            </li>
                                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                                <a class="link-nav navAlianzas <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'link-nav_active' : '' ?>" href="/preguntas-frecuentes">
                                                    <img src="/assets/icons/menu/ico-preguntas-menu.svg" alt="">
                                                    Preguntas frecuentes</a>
                                            </li>
                                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                                <a class="link-nav navAlianzas <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'link-nav_active' : '' ?>" href="/exit">
                                                    <img src="/assets/icons/menu/ico-logout-menu.svg" alt="">
                                                    Cerrar sesión</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                <a class="link-nav navAlianzas <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'link-nav_active' : '' ?>" href="/">
                                    <img src="/assets/icons/menu/ico-logout-menu.svg" alt="">
                                    Volver al inicio</a>
                            </li>
                        <?php } ?>

                    </ul>
            </div>

        </div>
    </div>
    </div>

</header>


<nav class="nav-responsive d-block d-md-none">
    <div class="menu-dropdown" id="submenu-mobile">
        <ul class="subMenuSecond">

            <li class="item-sub_menu <?php echo ($_SERVER['PHP_SELF'] == '/mis-redenciones.php') ? 'active' : ''; ?>">
                <a class="link-nav_ico <?php echo ($_SERVER['PHP_SELF'] == '/mis-redenciones.php') ? 'link-nav_active' : '' ?>" href="/redenciones">
                    <img src="/assets/icons/menu-mobile/ico-redenciones.svg" alt="">
                    Mis redenciones</a>
            </li>
            <li class="item-sub_menu <?php echo ($_SERVER['PHP_SELF'] == '/mis-redenciones.php') ? 'active' : ''; ?>">
                <a class="link-nav_ico <?php echo ($_SERVER['PHP_SELF'] == '/mis-redenciones.php') ? 'link-nav_active' : '' ?>" href="/como-ganar">
                    <img src="/assets/icons/menu-mobile/ico-ganar.svg" alt="">
                    ¿Cómo ganar?</a>
            </li>

            <li class="item-sub_menu <?php echo ($_SERVER['PHP_SELF'] == '/terminos-condiciones.php') ? 'active' : ''; ?>">
                <a class="link-nav_ico <?php echo ($_SERVER['PHP_SELF'] == '/terminos-condiciones.php') ? 'link-nav_active' : '' ?>" href="/terminos-condiciones">
                    <img src="/assets/icons/menu-mobile/ico-tyc.svg" alt="">
                    Términos y condiciones</a>
            </li>
            <li class="item-sub_menu <?php echo ($_SERVER['PHP_SELF'] == '/faq.php') ? 'active' : ''; ?>">
                <a class="link-nav_ico <?php echo ($_SERVER['PHP_SELF'] == '/faq.php') ? 'link-nav_active' : '' ?>" href="/preguntas-frecuentes">
                    <img src="/assets/icons/menu-mobile/ico-faq.svg" alt="">
                    Preguntas frecuentes</a>
            </li>

            <li class="item-sub_menu logout">
                <a class="link-nav_ico" href="/exit">
                    <img src="/assets/icons/menu-mobile/ico-logout.svg" alt="">
                    Cerrar Sesión</a>
            </li>
        </ul>
    </div>
    <ul class="d-flex align-center subMenuMain">
        <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? 'active' : ''; ?>">
            <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? 'link-nav_active' : '' ?>" href="/progreso"><img class="ico-menu" alt="Icono progreso" src="../assets/icons/ico-progreso.svg" />Progreso</a>
        </li>
        <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/como-ganar.php') ? 'active' : ''; ?>">
            <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/como-ganar.php') ? 'link-nav_active' : '' ?>" href="/promociones"><img class="ico-menu" alt="Icono progreso" src="../assets/icons/ico-progreso.svg" />Promociones</a>
        </li>

        <li class="item-menu <?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? 'active' : ''; ?>">
            <a class="link-nav <?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? 'link-nav_active' : '' ?>" href="/premios"><img class="ico-menu" alt="Icono progreso" src="../assets/icons/ico-progreso.svg" />Premios</a>
        </li>
        <li class="item-menu">
            <a id="show-submenu-mobile" class="link-nav show-submenu event-submenu"><img src="/assets/icons/menu/down-menu.svg" alt="">Más </a>
        </li>
    </ul>
</nav>