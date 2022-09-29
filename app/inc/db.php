<?php
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/logger.class.php";

class ChefDB
{
  protected $mysqli;
  protected $site;

  public function __construct()
  {
    try {
      $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
      $dotenv->load();
      $this->site = $_ENV['SITE_URL'];
      $this->mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB']);
      mysqli_set_charset($this->mysqli, 'utf8');
    } catch (mysqli_sql_exception $e) {
      http_response_code(500);
      exit;
    }
  }

  public function postLogin($user)
  {
    $Logger= new Logger();
    if (preg_match("/(>|=|<+)/", $user)) {
      header('Location: ' . $this->site . '/exit');
      exit;
    } else {
      $query = "SELECT u.* FROM mc_users u
      INNER JOIN mc_codes c ON u.idmask  = c.idmask
      WHERE c.code_hash = '$user'
      LIMIT 1";
      $result = $this->mysqli->query($query);
      $consulta = $result->fetch_assoc();
      $ip = $_SERVER["REMOTE_ADDR"];
      if ($consulta == "") {
        $stmt = $this->mysqli->prepare("INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $user . "',0, '" . $ip . "','" . date('Y-m-d H:i:s') . "')");
        $stmt->execute();

        $log_data = json_encode(['user'=>$user,'type'=>0,'ip'=>$ip,'date'=>date('Y-m-d H:i:s')]);
        $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=login | data: $log_data | transaccion=Login,detail=LoginFail",1);

        header('Location: ' . $this->site . '/exit?login_error=1');
        exit;
      } else {

        $query = "SELECT * FROM `mc_invalid_codes` WHERE sha2(code,'256') = '" . $user . "'";
        $result = $this->mysqli->query($query)->fetch_assoc();
        if ($result) {
          header('Location: ' . $this->site . '/exit?code_expired=1');
          exit;
        }

        $idmask = $consulta['idmask'];
        $setting_campaign_blocks = (int) $this->getSettingByname('campaign_blocks');
        $user_block =  $this->getUserBlock($idmask, 1, $setting_campaign_blocks);

        $queryLog = "INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $idmask . "',1, '" . $ip . "', '" . date('Y-m-d H:i:s') . "')";
        $stmt = $this->mysqli->prepare($queryLog);
        $stmt->execute();

        $log_data = json_encode(['user'=>$idmask,'type'=>1,'ip'=>$ip,'date'=>date('Y-m-d H:i:s')]);
        $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=login | data: $log_data | transaccion=Login,detail=LoginSuccess",1);

        $canRedeem = $this->getCanRedeem($idmask);

        $new_session = $this->new_session($idmask);
        if (!$new_session) {
          header('Location: ' . $this->site . '/exit?session_error=1');
          exit;
        }

        $_SESSION["campaign_blocks"] =  $setting_campaign_blocks;
        $_SESSION["block"] = $user_block;
        $_SESSION["id"] = $idmask;
        $_SESSION["idmask"] = $idmask;
        $_SESSION["award_price"] = $consulta['award_'.$user_block];
        $_SESSION["hasRedemptions"] = count($this->getAllRedemptions($idmask)) > 0 ? true : false;
        $_SESSION['winner'] = $canRedeem;
        $_SESSION["utmweb"] = false;
      }
    }
  }

/**
   * Login con codigo
   * @param id codigo enviado cifrado en sha256
   */
  public function postLoginSHA($code)
  {
    if (preg_match("/(>|=|<+)/", $code)) {
      header('Location: ' . $this->site . '/exit');
      exit;
    } else {

      $query_sha = "SELECT * FROM mc_codes WHERE sha2(code, 256) = '" . $code . "' LIMIT 1";
      $result_sha = $this->mysqli->query($query_sha);
      $consulta_sha = $result_sha->fetch_assoc();
      $ip = $_SERVER["REMOTE_ADDR"];
      if ($consulta_sha == "") {
        $stmt = $this->mysqli->prepare("INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $code . "',2, '" . $ip . "','" . date('Y-m-d H:i:s') . "')");
        $stmt->execute();
        header('Location: ' . $this->site . '/exit');
        exit;
      }

      $idmask = $consulta_sha['idmask'];

      $query = "SELECT * FROM mc_users WHERE idmask = '" . $idmask . "' LIMIT 1";
      $result = $this->mysqli->query($query);
      $consulta = $result->fetch_assoc();
      $ip = $_SERVER["REMOTE_ADDR"];
      if ($consulta == "") {
        $stmt = $this->mysqli->prepare("INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $idmask . "',3, '" . $ip . "','" . date('Y-m-d H:i:s') . "')");
        $stmt->execute();
        header('Location: ' . $this->site . '/exit');
        exit;
      } else {

        $query = "SELECT * FROM `mc_invalid_codes` WHERE sha2(code,'256') = '" . $code . "'";
        $result = $this->mysqli->query($query)->fetch_assoc();
        if ($result) {
          header('Location: ' . $this->site . '/exit?code_expired=1');
          exit;
        }

        $idmask = $consulta['idmask'];
        $setting_campaign_blocks = (int) $this->getSettingByname('campaign_blocks');
        $user_block =  $this->getUserBlock($idmask, 1, $setting_campaign_blocks);

        $queryLog = "INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $idmask . "',4, '" . $ip . "', '" . date('Y-m-d H:i:s') . "')";
        $stmt = $this->mysqli->prepare($queryLog);
        $stmt->execute();

        $canRedeem = $this->getCanRedeem($idmask);

        $new_session = $this->new_session($idmask);
        if (!$new_session) {
          header('Location: ' . $this->site . '/exit?session_error=1');
          exit;
        }

        $_SESSION["campaign_blocks"] =  $setting_campaign_blocks;
        $_SESSION["block"] = $user_block;
        $_SESSION["id"] = $idmask;
        $_SESSION["idmask"] = $idmask;
        $_SESSION["award_price"] = $consulta['award_'.$user_block];
        $_SESSION["hasRedemptions"] = count($this->getAllRedemptions($idmask)) > 0 ? true : false;
        $_SESSION['winner'] = $canRedeem;
        $_SESSION["utmweb"] = false;
      }
    }
  }

  public function budgetLimitReached()
  {
    $conteo = $this->postCount();
    $conteo = $conteo->fetch_row();
    $budget_limit_reached = false;
    if ($conteo[1] >= $conteo[6]) {
        $budget_limit_reached = true;
    }
    return $budget_limit_reached;
  }

  public function postPremios()
  {
    $setting_value_factor = $this->getSettingByname('awards_value_factor');
    $award_price_factor = $setting_value_factor ? (int)$setting_value_factor : 1000;
    $field = 's' . $_SESSION['award_price'] / $award_price_factor;
    $query = "SHOW COLUMNS FROM `mc_awards` LIKE '" . $field . "'";
    $result = $this->mysqli->query($query);
    if ($result->num_rows > 0) {
      $query = "SELECT * FROM mc_awards WHERE " . $field . " IS NOT NULL AND " . $field . " > 0 ORDER BY name";
      return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    } else{
      return false;
    }
  }

  /**
   * Cargar premio por id
   */
  public function getOneAward($id, $cuota, $active = true)
  {
    $operator = $active ? '':'=';
    $setting_value_factor = $this->getSettingByname('awards_value_factor');
    $award_price_factor = $setting_value_factor ? (int)$setting_value_factor : 1000;
    $p = "s" . ($cuota / $award_price_factor);
    $query = "SHOW COLUMNS FROM `mc_awards` LIKE '" . $p . "'";
    $result = $this->mysqli->query($query);
    if ($result->num_rows > 0) {
      $query = "SELECT * FROM mc_awards WHERE id = " . $id . ' AND ' . $p . ' IS NOT NULL AND ' . $p . " >$operator 0";
      return $this->mysqli->query($query);
    } else{
      return false;
    }
  }

  /**
   * retorna las redenciones de un usuario
   */
  public function getOneRedemption($user, $block)
  {
    $query = "SELECT r.*, a.name, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN mc_awards a ON a.id = r.id_award WHERE idmask = '" . $user . "'  AND block='" . $block . "'";
    $gano = $this->mysqli->query($query);
    return $gano;
  }

  /**
   * retorna las redenciones de un usuario
   */
  public function getAllRedemptions($user)
  {
    $query = "SELECT r.*, a.name, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN mc_awards a ON a.id = r.id_award WHERE idmask = '" . $user . "'" ;
    $redemptions = $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    return $redemptions;
  }


  public function numRedemptionsByAward($value, $id_award)
  {
    $query = "SELECT count(1) total FROM mc_redemptions WHERE value = " . $value . " AND id_award = " . $id_award;
    $resultp = $this->mysqli->query($query)->fetch_assoc();
    return $resultp;
  }

  /**
   * carga todos los premios que esten activos y los desordena para mostrarlos en el slider del home -- por el momento --
   */
  public function premiosRand()
  {
    $resultp = $this->mysqli->query("SELECT * FROM premios WHERE s20 = 1 OR s25 = 1 OR s100 = 1 OR s50 = 1 OR s75 = 1 ORDER BY RAND()");
    return $resultp;
  }
  /**
   * Cargar premio por id
   */
  public function getPremio($id)
  {
    $resultp = $this->mysqli->query("SELECT * FROM mc_awards WHERE id = " . $id)->fetch_assoc();
    return $resultp;
  }

  public function postMeta($user)
  {
    $meta = $this->mysqli->query("SELECT * FROM metas WHERE idmask = '$user'  ");
    return $meta;
  }

  public function postSeguimiento($user)
  {
    $seguimiento = $this->mysqli->query("SELECT * FROM seguimiento WHERE idmask = '$user'  ");
    return $seguimiento;
  }
  public function postGano($user)
  {
    $query = "SELECT r.*, a.name, a.description, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN `mc_awards` a ON a.id = r.id_award  WHERE r.idmask = '" . $user . "'";
    $gano = $this->mysqli->query($query);
    return $gano;
  }

  public function postGanopremio($idp, $user, $idtxt, $json, $valor, $block)
  {
    $stmt = $this->mysqli->prepare("INSERT INTO mc_redemptions(id_award,idmask, date, value, idtxt, json, block)
                                    VALUES ('" . $idp . "','" . $user . "','" . date('Y-m-d H:i:s') . "','" . $valor . "','" . $idtxt . "','" . $json . "','" . $block . "')");
    $stmt->execute();
    return $stmt->insert_id;
  }

  public function postPremio($user)
  {
    $gano = $this->mysqli->query("SELECT * FROM bono WHERE idmask = '$user'  ");
    return $gano;
  }

  public function postlistPremios($p)
  {
    $p = "s" . $p;
    $premios = $this->mysqli->query("SELECT * FROM premios WHERE $p =1  ");
    return $premios;
  }
  public function postError($idu, $brand, $product, $type, $textResponse = '', $post_data = '')
  {
    $stmt = $this->mysqli->prepare("INSERT INTO mc_awards_logs(id_user,id_award,id_product_quantum,type_error, text_response, post_data, date)
    VALUES ('" . $idu . "','" . $brand . "','" . $product . "','" . $type . "', '" . $textResponse . "', '" . $post_data . "', '" . date('Y-m-d H:i:s') . "')");
    $r = $stmt->execute();

    $Logger= new Logger();
    $log_data = json_encode(['id_user'=>$idu,'id_award'=>$brand,'id_product_quantum'=>$product,'type_error'=> $type,'text_response'=> $textResponse,'post_data'=> $post_data,'date'=>date('Y-m-d H:i:s')]);
    $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=redemption | data: $log_data | transaccion=Login,detail=RedemptionLog",1);
  }

  public function postCount()
  {
    $gano = $this->mysqli->query("SELECT count(r.id),sum(r.value), n.* FROM `mc_redemptions` r, `mc_notifications_setup` n where n.id = 1");
    return $gano;
  }

  public function postCountByMount($mount, $year)
  {
    $gano = $this->mysqli->query("SELECT count(id),sum(value) FROM `mc_redemptions` where MONTH(date) = " . $mount . " AND YEAR(date) = " . $year);
    return $gano;
  }

  /**
   * get data all users report
   */
  public function getTotalPremios()
  {

    $report = $this->mysqli->query("SELECT count(id) as numero_premios ,sum(cuota) as total_entregados  FROM bono ");
    return $report;
  }

  /**
   * Get user award
   */

  public function getUserByIdAward($id)
  {
    $res = $this->mysqli->query("SELECT u.nombre as usuario ,b.fecha, b.cuota, p.nombre as premio FROM usuarios u 
          inner join bono b on u.idmask = b.idmask 
          inner join premios p on b.idp = p.idq WHERE u.idmask = '" . $id . "'");
    return $res;
  }
  public function getUserLoginById($id)
  {
    $res = $this->mysqli->query("SELECT u.idmask, u.nombre, l.date, l.tipo  FROM usuarios u inner  join login l on u.idmask = l.idmask WHERE u.idmask = '" . $id . "'");
    return $res;
  }
  /**
   * actualizar campo con la siguiente concurrencia de notificacion
   */
  public function updateSiguienteNotificacion($id = 1)
  {
    $query = "UPDATE mc_notifications_setup SET current = current + concurrence WHERE id = " . $id;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  public function getBadRedention($value, $award)
  {
    $query = "SELECT * FROM `mc_redemptions_bad` where value = '" . $value . "' AND id_award = " . $award . " AND idmask = 0 LIMIT 1";
    return $this->mysqli->query($query);
  }
  /**
   * actualizar idmask en bad redemption
   */
  public function updateBadRedemption($id, $idmask)
  {
    $query = "UPDATE mc_redemptions_bad SET idmask = '" . $idmask . "' WHERE id = " . $id;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * All login
   */
  public function loginReport()
  {
    $report = $this->mysqli->query("SELECT l.idmask, l.date as fecha, l.ip FROM mc_login l where l.ip <> '181.141.237.226' AND l.ip <> '181.57.146.242' AND l.type IN (1,4)");
    return $report;
  }

  /**
   * Listar login report
   */
  public function redencionReport()
  {
    $report = $this->mysqli->query("SELECT r.idmask, p.name AS nombre, r.value, r.date AS fecha FROM mc_redemptions r INNER JOIN mc_awards p ON r.id_award = p.id;");
    return $report;
  }

  /**
   * validar el codigo que se ingresa es válido
   * @param code codigo cifrado a validar
   */
  public function validateCode($code)
  {
    if (preg_match("/(>|=|<+)/", $code)) {
      header('Location: ' . $this->site . '/exit');
      exit;
    } else {
      $query = "SELECT * FROM mc_codes WHERE code = '" . $code . "' AND idmask = '" . $_SESSION["idmask"] . "' LIMIT 1";
      $result = $this->mysqli->query($query);
      $consulta = $result->fetch_row();
      if (empty($consulta)) {
        return false;
      }
      return true;
    }
  }

  #region [Funciones Mecánica Financiera OH]

  public function getUserTracing($idmask)
  {
    $result = $this->mysqli->query("SELECT * FROM mc_tracing WHERE idmask = '$idmask' ");
    $tracing = $result->fetch_assoc();
    return $tracing;
  }

  public function getIsWinner($idmask, $block)
  {
    $is_winner = false;
    $user_tracing = $this->getUserTracing($idmask);
    $winner_block = isset($user_tracing['winner_' . $block]) ? (int) $user_tracing['winner_' . $block]:0;
    if ($winner_block == 1) {
      $is_winner = true;
    }
    return $is_winner;
  }

  public function getCanRedeem($idmask)
  {
    $canRedeem = false;
    $setting_campaign_blocks = (int) $this->getSettingByname('campaign_blocks');
    $user_block =  $this->getUserBlock($idmask, 1, $setting_campaign_blocks);
    $is_winner = $this->getIsWinner($idmask, $user_block);
    $redemption = $this->getOneRedemption($idmask, $user_block);
    $redemption = $redemption->fetch_assoc();

    if ($is_winner && !$redemption) {
      $canRedeem = true;
    }

    return $canRedeem;
  }

  public function getTextInfo($type, $block = false)
  {
    if ($block) {
      $result = $this->mysqli->query("SELECT text FROM mc_text WHERE type = '$type' AND block = '$block'");
    } else {
      $result = $this->mysqli->query("SELECT text FROM mc_text WHERE type = '$type' ");
    }
    $text_info = $result->fetch_assoc();

    return $text_info['text'];
  }

  public function getUserGoal($idmask)
  {
    $result = $this->mysqli->query("SELECT * FROM mc_users WHERE idmask = '$idmask'");
    $goal = $result->fetch_assoc();
    return $goal;
  }

  public function getUserBlock($idmask, $start_block = 1, $end_block = 4)
  {
    $start_block = (int) $start_block;
    $end_block = (int) $end_block;
    $userTracing = $this->getUserTracing($idmask);
    $user_block = $start_block;
    for ($i = $start_block; $i <= $end_block; $i++) {
      if (isset($userTracing['winner_' . $i]) && (int)$userTracing['winner_' . $i] == 1) {
        $redemption = $this->getOneRedemption($idmask, $i);
        $redemption = $redemption->fetch_assoc();
        if (!$redemption) {
          $user_block = $i;
          break;
        } elseif ($redemption && $user_block < $end_block) {
          $user_block++;
        }
      }
    }
    return $user_block;
  }

  public function isFirstLogin($idmask)
  {
    $query = "SELECT count(idmask) total FROM mc_login WHERE idmask = '$idmask'";
    $result = $this->mysqli->query($query)->fetch_assoc();
    $countLogins = (int)$result['total'];
    $isFirstLogin = ($countLogins == 1) ? true : false;
    return $isFirstLogin;
  }

  #endregion [Funciones Mecánica Financiera OH]

  #region [Generación de codigos]

  /**
   * Inserta Idmask en tabla mc_codes
   */
  public function InsertUsersIntoCodesTable()
  {
    $query = "INSERT INTO mc_codes (idmask)
    SELECT idmask
    FROM mc_users
    WHERE idmask not in (SELECT idmask FROM mc_codes);";
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
    return $stmt->affected_rows;
  }


  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function getUsersCodeNull()
  {
    $query = "SELECT * FROM `mc_codes` WHERE code IS NULL";
    return $this->mysqli->query($query);
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function insertCode($idmask, $code)
  {
    $query = "SELECT * FROM `mc_codes` WHERE code = '" . $code . "'";
    $data = $this->mysqli->query($query)->fetch_array();
    if ($data) {
      return false;
    } else {
      $query = "UPDATE `mc_codes` SET code = '" . $code . "', code_hash = sha2('" . $code . "','256') WHERE idmask = '" . $idmask . "'";
      $stmt = $this->mysqli->prepare($query);
      $stmt->execute();
      return true;
    }
  }
  #endregion [Generación de codigos]


  #region [Funciones Puntos Leal]
  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param integer $product id del producto
   * @return Array registro unico en caso de estar disponible
   */
  public function getNextCod($product = false, $value)
  {
    $query = "SELECT * FROM `mc_leal_cods` where id_award = " . $product . " AND value = '" . $value . "' AND idmask IS NULL LIMIT 1";
    return $this->mysqli->query($query)->fetch_assoc();
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function saveNextCod($idmask = false, $idcod = false)
  {
    $query = "UPDATE `mc_leal_cods` SET idmask = '" . $idmask . "' WHERE id = " . $idcod;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * actualizar idmask en bad redemption
   */
  public function updateAwardStock($id_award, $value)
  {
    $query = "UPDATE mc_leal_stock SET consumed = consumed + 1 WHERE id_award = '" . $id_award . "' AND value = '" . $value . "'";
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param integer $product id del producto
   * @return Array registro unico en caso de estar disponible
   */
  public function checkStockRemain($product = false, $value)
  {
    $query = "SELECT s.*, (total - consumed) subtraction, a.name FROM `mc_leal_stock` s " .
      "INNER JOIN mc_awards a ON a.id = s.id_award " .
      "WHERE id_award = " . $product . " AND s.value = '" . $value . "'";
    return $this->mysqli->query($query)->fetch_assoc();
  }


  #endregion [Funciones Puntos Leal]

  /**
   * Retorna settings por nombre
  */
  public function getSettingByname($name)
  {
    $query = "SELECT value FROM `mc_settings` where name = '$name'";
    $resultSetting = $this->mysqli->query($query)->fetch_row();
    return ($resultSetting) ? $resultSetting[0] : false;
  }

  /**
   * Retorna lista whitelist por tipo
   */
  public function get_whitelist($type)
  {
    $query = "SELECT ip FROM `mc_whitelist` where type = '" . $type. "'";
    $result = $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    $whitelist = array_column($result,'ip');
    return $whitelist;
  }

  #region [unique session]

  public function get_session($idmask)
  {
    $querySession = "SELECT session_id FROM mc_sessions WHERE idmask = '$idmask' LIMIT 1";
    $querySessionResult = $this->mysqli->query($querySession)->fetch_row();
    $session_id = ($querySessionResult) ? $querySessionResult[0] : false;
    return $session_id;
  }

  public function save_session($idmask)
  {
    $querySession = "INSERT INTO  mc_sessions(idmask,session_id, date)
    VALUES ('" . $idmask . "','" . $_COOKIE["PHPSESSID"] . "', '" . date('Y-m-d H:i:s') . "')";
    $querySessionResult = $this->mysqli->prepare($querySession);
    $querySessionResult->execute();
    return $querySessionResult->affected_rows == 1 ? true : false;
  }

  public function update_session($idmask)
  {
    $querySession = "UPDATE `mc_sessions` SET session_id = '" . $_COOKIE["PHPSESSID"] . "' WHERE idmask = '" . $idmask . "'";
    $querySessionResult = $this->mysqli->prepare($querySession);
    $querySessionResult->execute();
    return $querySessionResult->affected_rows == 1 ? true : false;
  }

  public function new_session($idmask)
  {
    $get_session = $this->get_session($idmask);
    if($get_session){
      $new_session = $this->update_session($idmask);
    }
    else{
      $new_session = $this->save_session($idmask);
    }
    return $new_session;
  }

  public function unique_session($idmask)
  {
    $active_session = $this->get_session($idmask);
    $unique_session = ($active_session == $_COOKIE["PHPSESSID"]) ? true : false;
    return $unique_session;
  }

  #endregion [unique session]
}