<?php
    $setting_key_g_recaptcha_front = $db->getSettingByname('key-g-recaptcha-front');
    $key_g_recaptcha_front = $setting_key_g_recaptcha_front !== '' ? trim($db->getSettingByname('key-g-recaptcha-front')) : false;

    if ($key_g_recaptcha_front) {
?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $key_g_recaptcha_front; ?>"></script>
    <script text="text/javascript" nonce="<?php echo $cspNonce[2]; ?>">
        $(document).ready(function() {
            createNewToken();

            function createNewToken() {
                grecaptcha.ready(function() {
                    grecaptcha
                        .execute("<?php echo $key_g_recaptcha_front; ?>", {
                            action: "index_login",
                        })
                        .then(function(token) {
                            document.getElementById("g-recaptcha-response").value = token;
                        });
                });

            }
        })
    </script>
<?php
    }
?>