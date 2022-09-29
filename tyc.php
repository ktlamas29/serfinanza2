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
    <title>Términos y condiciones | Serfinanza</title>
</head>

<body>
    <?php
    include __DIR__ . '/partiales/header.php';
    ?>
    <div class="main-content">
        <section class="banner-int-tyc">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-6 ">
                        <div class="wrap_tyc">
                            <h1 class="title_tyc">
                                <p class="color-red font-60">
                                    <strong>Términos</strong>
                                </p>
                                <p class="text-right font-50 color-blue condiciones">
                                    <strong>y condiciones</strong>
                                </p>
                            </h1>
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-md-6 ">
                        <div>
                            <img src="assets/banners/banner-tyc.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content_tyc container">
            <article>
                <h3>I. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h3>
                <p>
                    Ut porta malesuada aliquam. In sed dui vitae nisl lobortis hendrerit id id ligula. In porttitor sed tellus quis sollicitudin. Proin rhoncus placerat mauris, in tincidunt arcu egestas sed. Maecenas hendrerit erat sed ex dignissim, eget euismod arcu mattis. Cras facilisis est ac purus rutrum, ut aliquam leo gravida. Fusce a erat justo. Nullam pulvinar semper neque id facilisis. In hac habitasse platea dictumst. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec pellentesque viverra nulla, accumsan aliquam est hendrerit ac. Nulla a ante volutpat, maximus urna ultrices, sollicitudin massa. Nullam vitae velit eu mi varius imperdiet. Cras nec nulla diam. Duis non purus ligula. Cras lobortis sem quis urna dictum condimentum. Donec diam lacus, bibendum in bibendum eu, ultrices a neque. Integer molestie sapien vitae eros suscipit, ac convallis turpis efficitur. Sed at rhoncus purus, aliquet vulputate est. Curabitur tempus lobortis malesuada. Phasellus massa velit, ullamcorper et ultricies ac, blandit eu diam. Donec quis turpis eget nisl venenatis volutpat ac et lectus. Mauris sagittis quam vel orci semper blandit. Nunc vehicula ex nunc, ac eleifend augue feugiat et. Curabitur viverra mi eros, vel suscipit quam maximus eu. Integer elementum est porta velit vestibulum, eget convallis velit euismod. Nam sit amet purus placerat, accumsan ante ac, vehicula metus. In lacinia neque vel vestibulum gravida. Aliquam ac orci quis nunc imperdiet egestas quis sed.
                </p>
                <p>
                    Ut porta malesuada aliquam. In sed dui vitae nisl lobortis hendrerit id id ligula. In porttitor sed tellus quis sollicitudin. Proin rhoncus placerat mauris, in tincidunt arcu egestas sed. Maecenas hendrerit erat sed ex dignissim, eget euismod arcu mattis. Cras facilisis est ac purus rutrum, ut aliquam leo gravida. Fusce a erat justo. Nullam pulvinar semper neque id facilisis. In hac habitasse platea dictumst. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec pellentesque viverra nulla, accumsan aliquam est hendrerit ac. Nulla a ante volutpat, maximus urna ultrices, sollicitudin massa. Nullam vitae velit eu mi varius imperdiet. Cras nec nulla diam. Duis non purus ligula. Cras lobortis sem quis urna dictum condimentum. Donec diam lacus, bibendum in bibendum eu, ultrices a neque. Integer molestie sapien vitae eros suscipit, ac convallis turpis efficitur. Sed at rhoncus purus, aliquet vulputate est. Curabitur tempus lobortis malesuada. Phasellus massa velit, ullamcorper et ultricies ac, blandit eu diam. Donec quis turpis eget nisl venenatis volutpat ac et lectus. Mauris sagittis quam vel orci semper blandit. Nunc vehicula ex nunc, ac eleifend augue feugiat et. Curabitur viverra mi eros, vel suscipit quam maximus eu. Integer elementum est porta velit vestibulum, eget convallis velit euismod. Nam sit amet purus placerat, accumsan ante ac, vehicula metus. In lacinia neque vel vestibulum gravida. Aliquam ac orci quis nunc imperdiet egestas quis sed.
                </p>
                <p>
                    Ut porta malesuada aliquam. In sed dui vitae nisl lobortis hendrerit id id ligula. In porttitor sed tellus quis sollicitudin. Proin rhoncus placerat mauris, in tincidunt arcu egestas sed. Maecenas hendrerit erat sed ex dignissim, eget euismod arcu mattis. Cras facilisis est ac purus rutrum, ut aliquam leo gravida. Fusce a erat justo. Nullam pulvinar semper neque id facilisis. In hac habitasse platea dictumst. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec pellentesque viverra nulla, accumsan aliquam est hendrerit ac. Nulla a ante volutpat, maximus urna ultrices, sollicitudin massa. Nullam vitae velit eu mi varius imperdiet. Cras nec nulla diam. Duis non purus ligula. Cras lobortis sem quis urna dictum condimentum. Donec diam lacus, bibendum in bibendum eu, ultrices a neque. Integer molestie sapien vitae eros suscipit, ac convallis turpis efficitur. Sed at rhoncus purus, aliquet vulputate est. Curabitur tempus lobortis malesuada. Phasellus massa velit, ullamcorper et ultricies ac, blandit eu diam. Donec quis turpis eget nisl venenatis volutpat ac et lectus. Mauris sagittis quam vel orci semper blandit. Nunc vehicula ex nunc, ac eleifend augue feugiat et. Curabitur viverra mi eros, vel suscipit quam maximus eu. Integer elementum est porta velit vestibulum, eget convallis velit euismod. Nam sit amet purus placerat, accumsan ante ac, vehicula metus. In lacinia neque vel vestibulum gravida. Aliquam ac orci quis nunc imperdiet egestas quis sed.
                </p>
            </article>

        </section>
    </div>





    <?php
    include __DIR__ . '/partiales/footer.php';

    include __DIR__ . '/partiales/assets-js.php';
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