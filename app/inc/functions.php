<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/db.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function logOut($redirect = true)
{
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
    if ($redirect) {
        header('Location: /');
        exit;
    } else {
        return true;
    }
}
function test_input($data, $cant)
{
    $tama = strlen(stripslashes(htmlspecialchars($data)));
    $data = trim($data);
    if (preg_match("/(>|=|<+)/", $data)  or $tama != $cant  or is_int($data)) {
        header('Location: ./');
        exit;
    } else {
        return $data;
    }
}
function test_input2($data)
{
    $data = trim($data);
    if (preg_match("/(>|=|<+)/", $data)) {
        header('Location: ./');
        exit;
    } else {
        return $data;
    }
}

function explodeDate($date)
{
    $dateArray = explode('/', $date);
    $formatDateString = trim($dateArray[2]) . '-' . trim($dateArray[1]) . '-' . trim($dateArray[0]);
    return strtotime($formatDateString);
}
function getMonth($number)
{
    switch ($number) {
        case 1:
        case '01':
            return 'enero';
            break;
        case 2:
        case '02':
            return 'febrero';
            break;
        case 3:
        case '03':
            return 'marzo';
            break;
        case 4:
        case '04':
            return 'abril';
            break;
        case 5:
        case '05':
            return 'mayo';
            break;
        case 6:
        case '06':
            return 'junio';
            break;
        case 7:
        case '07':
            return 'julio';
            break;
        case 8:
        case '08':
            return 'agosto';
            break;
        case 9:
        case '09':
            return 'septiembre';
            break;
        case 10:
            return 'octubre';
            break;
        case 11:
            return 'noviembre';
            break;
        case 12:
            return 'diciembre';
        default:
            return '';
            break;
    }
}

function sendEmail($to, $message, $subject)
{
    try {
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = false;                                   // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $_ENV['PHPMAILER_USER'];                // SMTP username
        $mail->Password   = $_ENV['PHPMAILER_PASSWORD'];            // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom('campanasmastercard@gmail.com', 'Campañas MasterCard');
        if (gettype($to) == 'array') {
            foreach ($to as $email) {
                $mail->addAddress(trim($email));
            }
        } else {
            $mail->addAddress(trim($to));
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * @param  String $str The input string
 * @return String      The string without accents
 */
function removeAccents($str)
{
    $a = array(
        'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
        'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
        'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
        'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
        'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
        'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
        'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
        'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
        'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
        'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
        'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
        'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'
    );
    $b = array(
        'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
        'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
        'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
        'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
        'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
        'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
        'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
        's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
        'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
        'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'
    );
    return str_replace($a, $b, $str);
}
/**
 * @param  String $str The input string
 * @return String      The URL-friendly string (lower-cased, accent-stripped,
 *                     spaces to dashes).
 */
function toURLFriendly($str)
{
    $str = removeAccents($str);
    $str = preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'), array('', '-', ''), $str);
    $str = preg_replace('/-inc$/i', '', $str);
    return strtolower($str);
}

function recaptcha_validate($response)
{
    require_once __DIR__ . "/./db.php";
    $db = new ChefDB();
    $setting_key_g_recaptcha_secret = $db->getSettingByname('key-g-recaptcha-secret');
    $key_g_recaptcha_secret = $setting_key_g_recaptcha_secret !== '' ? trim($setting_key_g_recaptcha_secret) : false;

    $secret = $key_g_recaptcha_secret;
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secret, 'response' => $response, 'remoteip' => $ip);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        return false;
    } else {
        return true;
    }
}

/**
 * cortar cadena en cierto numero de caracteres
 */
function cutText($text, $length = 140)
{
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '. <strong>Ver mas...</strong>';
    }
    return $text;
}
/**
 * cortar cadena en cierto numero de palabras
 */
function getSnippet($str, $wordCount = 10)
{
    return implode('', array_slice(preg_split('/([\s,\.;\?\!]+)/', $str, $wordCount * 2 + 1, PREG_SPLIT_DELIM_CAPTURE), 0, $wordCount * 2 - 1));
}


function progressReplaceCard2Values($card_2_html, $redemption)
{
    $db = new ChefDB();
    $award = $db->getOneAward($redemption['id_award'], $redemption['value'],false)->fetch_assoc();
    $date = new DateTime($redemption['date']);

    $card_2_html = str_replace(
        [
            '@logo@',
            '@logo_alt@',
            '@name@',
            '@price@',
            '@block@',
            '@date_text@',
            '@description@',
            '@image@'
        ],
        [
            $redemption['logo_image'],
            $redemption['name'],
            $redemption['name'],
            number_format($redemption['value'], 0, ',', '.'),
            $redemption['block'],
            $date->format('Y-m-d'),
            $award['description'],
            $award['image']
        ],
        $card_2_html
    );
    return $card_2_html;
}
