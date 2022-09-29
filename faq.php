<?php
require_once __DIR__ . "/app/inc/security.php";
require_once __DIR__ . "/app/inc/server.php";
require_once __DIR__ . "/app/inc/functions.php";
require_once __DIR__ . "/app/inc/db.php";

$sessionUser = isset($_SESSION['idmask']);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include __DIR__ . '/app/partials/metadata.php';
  include __DIR__ . '/partiales/assets-css.php';
  ?>

  <title> Preguntas frecuentes | Serfinanza</title>
</head>

<body>

  <?php
  include __DIR__ . '/partiales/header.php';
  ?>
  <div class="main-content">
    <section class="banner-int-faq">
      <div class="container">
        <div class="row">
          <div class="col-12 col-lg-6 ">
            <div class="wrap_faq">
              <h1 class="title_faq">
                <p class="color-red font-60">
                  <strong>Preguntas</strong>
                </p>
                <p class="text-right font-50 color-blue frecuentes">
                  <strong>frecuentes</strong>
                </p>
              </h1>
            </div>
          </div>
          <div class="d-none d-lg-block col-md-6 ">
            <div>
              <img src="assets/banners/banner-faq.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="accordion">
              <div class="accordion-content open">
                <header>
                  <h3 class="title"><strong>¿Lorem ipsum dolor sit amet, consectetur adipiscing elit?</strong></h3>
                  <i class="fa-minus"></i>
                </header>

                <p class="description">
                  Ut porta malesuada aliquam. In sed dui vitae nisl lobortis hendrerit id id ligula. In porttitor sed tellus quis sollicitudin. Proin rhoncus placerat mauris, in tincidunt arcu egestas sed. Maecenas hendrerit erat sed ex dignissim, eget euismod arcu mattis. Cras facilisis est ac purus rutrum, ut aliquam leo gravida. Fusce a erat justo. Nullam pulvinar semper neque id facilisis. In hac habitasse platea dictumst.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


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