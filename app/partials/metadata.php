    <?php
    require_once "./app/inc/db.php";
    $db = new ChefDB();

    $csp_nonce = base64_encode(random_bytes((20)));
    $cspNonce = [];
    $cspNonceStyles = [];

    for ($i = 0; $i < 5; $i++) {
        $cspNonce[] = base64_encode(random_bytes((20)));
        $cspNonceStyles[] = base64_encode(random_bytes((20)));
    }
    $server_name = $_SERVER['SERVER_NAME'];

    $setting_gtm_enable = $db->getSettingByname('GTM_ENABLE');
    $gtm_enable = $setting_gtm_enable == '1' ? true : false;

    $setting_gtm_id = trim($db->getSettingByname('GTM_ID')) !== '' ? trim($db->getSettingByname('GTM_ID')) : false;
    $gtm_id = $setting_gtm_id
    ?>

    <!-- Google Tag Manager -->
    <?php
    if ($gtm_enable && $gtm_id) {
    ?>
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?php echo $gtm_id ?>');
        </script>
    <?php
    }
    ?>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="/favicon.ico">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' data: gap: ws: ; 
    style-src 'self'<?php foreach ($cspNonceStyles as $noncehashStyle) {
                        echo ' \'' . 'nonce-' . $noncehashStyle . '\'';
                    } ?> https: *.googleapis.com https: *.gstatic.com https: *.google.com; 
    script-src 'self'<?php foreach ($cspNonce as $noncehash) {
                            echo ' \'' . 'nonce-' . $noncehash . '\'';
                        } ?> https: *.googleapis.com https: *.gstatic.com https: *.google.com;
    media-src 'none'; 
    font-src 'self';
    frame-src *;
    connect-src *;
    img-src 'self' https: *.googleapis.com https: *.gstatic.com https: *.google.com https: *.<?php echo $server_name ?>;" />