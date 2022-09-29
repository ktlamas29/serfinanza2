<?php
require_once __DIR__ . "/./db.php";
$db = new ChefDB();
$code_generator_whitelist = $db->get_whitelist('code-generator');
if (in_array($_SERVER['REMOTE_ADDR'],$code_generator_whitelist)){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);
require_once __DIR__ . "/server.php";
require_once __DIR__ . "/security.php";
require_once __DIR__ . "/functions.php";
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * genera pdf con los datos de un producto
 * @param Array $product datos del producto
 * @return Array
 */
function generateVoucherPDF($product, $nextCod)
{

    $code = $nextCod['code'];
    $valid_date = $nextCod['valid_date'];
    $security_code = $nextCod['security_code'];
    $url = $nextCod['url'];

    $price = 'S/ ' . number_format($_SESSION['award_price'], 0, ',', '.');

    $path = __DIR__ . '/../../mpdf';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    $mpdf = new \Mpdf\Mpdf([
        'default_font_size' => 9,
        'default_font' => 'dejavusans',
        'tempDir' => __DIR__ . '/../../mpdf'
    ]);

    $stylesheet = '';


    $bonoHtml = '
    <!DOCTYPE html>
    <html>
    
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" />
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    </head>
    
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
      <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
          <tr>
            <td align="center" valign="top" id="bodyCell">
              <!-- BEGIN TEMPLATE // -->
              <table border="0" cellpadding="0" cellspacing="0" id="templateContainer">
                <tr>
                  <td align="center" valign="top">
                    <!-- BEGIN HEADER // -->
                    <table bgcolor="" border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
                      <tr>
                        <td align="center">
                          <img src="/assets/pdf-img/header.png" width="600" style="width: 600px" alt="" />
                        </td>
                      </tr>
                    </table>
                    <!-- // END HEADER -->
                  </td>
                </tr>
    
                <tr>
                  <td style="padding-top: 4px;" bgcolor="" align="center" valign="top">
                    <!-- BEGIN BODY // -->
                    <table bgcolor="#FFFFFF" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" id="">
    
                      <tr>
                        <td>
                          <table bgcolor="#047DBA" align="center" width="600" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center" style="color: #FFFFFF;font-size: 80px;font-weight: bold;padding-top: 30px;padding-bottom: 30px;">
                              ' . $product['name'] .'
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
    
                      <tr>
                        <td style="padding-top: 30px; font-size: 28px; text-align: center;" align="center">
                          Bono por valor de
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 48px; font-weight: bold;color: #047DBA;" align="center">
                          ' . $price . '
                        </td>
                      </tr>';


                    if (!empty($url)) {
                      $bonoHtml .=                       
                      '
                      <tr>
                        <td style="padding-top: 20px; text-align: center;" align="center">
                          <img src="/assets/pdf-img/linea.png" alt="">
                        </td>
                      </tr>
    
                      <tr>
                        <td style="padding-top: 30px; font-size: 28px; text-align: center;" align="center">
                          Ingresa aquí para canjear tu bono
                        </td>
                      </tr>
                      <tr>
    
                        <td style="font-size: 28px;color: #1116FF; text-align: center;" align="center">
                          <a style="color: #1116FF;text-decoration: none;" href="' . $url . '" target="_blank" rel="noopener noreferrer">
                          ' . $url . '
                          </a>
                        </td>
                      </tr>';
                    }

                  if ($valid_date && $valid_date != '0000-00-00') {
                    $bonoHtml .=                       
                    '
                    <tr>
                      <td style="padding-top: 20px; text-align: center;" align="center">
                        <img src="/assets/pdf-img/linea.png" alt="">
                      </td>
                    </tr>
  
                    <tr>
                      <td style="padding-top: 30px; font-size: 28px; text-align: center;" align="center">
                        Fecha de expiración
                      </td>
                    </tr>
  
  
                    <tr>
                      <td style="text-align: center; font-size: 48px;color: #047DBA;" align="center">
                      ' . $valid_date . '
                      </td>
                    </tr>';
                  }
    
                      $bonoHtml .=                       
                      '
                      <tr>
                        <td>
                          <table bgcolor="#EF0100" style="background: #EF0100;" align="center" width="600" border="0"
                            cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center"
                                style="font-size: 28px; padding-top: 5px;padding-bottom: 10px; text-align: center;color: #FFFFFF;">
                                <a style="color: #FFFFFF;text-decoration: none;" href="" target="_blank"
                                  rel="noopener noreferrer">
                                  
                                </a>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td style="padding-top: 30px; font-size: 28px; text-align: center;" align="center">
                          Código de validación
                        </td>
                      </tr>
    
                      <tr>
                        <td style="padding-top: 10px; text-align: center; font-size: 48px;color: #047DBA;" align="center">
                        ' . $code . '
                        </td>
                      </tr>';

                    if (!empty($security_code)) {
                      $bonoHtml .=                       
                      '
                      <tr>
                        <td style="padding-top: 10px; text-align: center;" align="center">
                          <img src="/assets/pdf-img/linea.png" alt="">
                        </td>
                      </tr>

                      <tr>
                        <td style="padding-top: 30px; font-size: 28px; text-align: center;" align="center">
                          Código de seguridad
                        </td>
                      </tr>
    
                      <tr>
                        <td style="padding-top: 10px; text-align: center; font-size: 48px;color: #047DBA;" align="center">
                        ' . $security_code . '
                        </td>
                      </tr>';               
                    }


                      
                      $bonoHtml .=                       
                      '
                      <tr>
                        <td style="padding-top: 10px; text-align: center;" align="center">
                          <img src="/assets/pdf-img/linea.png" alt="">
                        </td>
                      </tr>
    
    
                    </table>
                    <!-- // END BODY -->
                  </td>
                </tr>
    
                <tr>
                  <td align="center" valign="top">
                    <!-- BEGIN FOOTER // -->
                    <table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter">
                      <tr>
                        <td style="padding-top: 20px">';


            if ($product['terms']) {
              $bonoHtml .='                        
              <table width="600" align="center" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding-left: 20px;text-align: left;" align="left">
                    Términos y condiciones
                  </td>
                </tr>
                <tr>
                  <td style="padding-left: 20px; text-align: left;padding-top: 10px;" align="left">
                    '.$product['terms'].'
                  </td>
                </tr>
              </table>';
            }

            $bonoHtml .=      
                          '
                        </td>
                      </tr>
                    </table>
                    <!-- // END FOOTER -->
                  </td>
                </tr>
    
                <tr>
                  <td style="padding-top: 30px"></td>
                </tr>
              </table>
              <!-- // END TEMPLATE -->
            </td>
          </tr>
        </table>
      </center>
    </body>
    
    </html>
    ';    

    $path = '/voucher_leal';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    $stylesheet = file_get_contents('/css/pdf-voucher.css');
    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($bonoHtml, \Mpdf\HTMLParserMode::HTML_BODY);
    $filename = $_SESSION["idmask"] . '-' . toURLFriendly($product['name']) . '-' . $_SESSION["block"] . '.pdf';
    $mpdf->Output('./voucher_leal/' . $filename, 'F');
    // $mpdf->Output($filename, 'D');
    return [
        'filename' => $filename,
        'fileurl' => '/voucher_leal/' . $filename
    ];
}
