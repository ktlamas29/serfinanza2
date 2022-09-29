<?php
require_once __DIR__ . "/../../vendor/autoload.php";
/*
	require_once __DIR__ . "/logger.class.php";
	$Logger= new Logger();
	$Logger->log(__FILE__, __LINE__,"| category=error | idMessage=PRUEBAMENSAJE | data: PRUEBADATA | transaccion=crearUsuario,detail=XMLMalFormado",1);
	$Logger->log(__FILE__, __LINE__,"category=error | idMessage=$idMensaje[0] | data: $data | transaccion=crearUsuario,detail=XMLMalFormado,");

	NOTICE
	WARNING
	ERROR
	FATAL
	LOG
*/

	class Logger
	{
		private $ini;
		private $dia;
		protected $mysqli;

		function __construct()
		{
				$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
				$dotenv->load();

				$this->dia = date("Y-m-d");
				$this->ini = [
						"host" => $_ENV['LOGS_DB_HOST'],
						"db"=> $_ENV['LOGS_DB'],
						"user" => $_ENV['LOGS_DB_USER'],
						"password" => $_ENV['LOGS_DB_PASSWORD'],
						"pathLogs" => $_ENV['LOGS_PATH'] // Se debe crear la carpeta con permisos manualmente e indicar el path en el .env
					   ];
		}

		function connectionDB(){
			try {
				$this->mysqli = new mysqli($this->ini['host'], $this->ini['user'], $this->ini['password'], $this->ini['db']);
				return $this->mysqli;
			} catch (\Throwable $th) {
				http_response_code(500);
				exit;
			}
		}

		/**
		* @ funcion--> funcion o metodo que origina el log
		* @ linea --> linea del archivo que origina el log
		* @ mensajeError--> mensaje de error que origina el log
		* @ typeLog 0->no haga nada 1-> archivo 2 loguee en bd 3 logue en ambos
		**/
		function log($funcion, $linea, $mensaje, $typeLog)
		{
			if($typeLog==2 || $typeLog==3 ){
				$conn=$this->connectionDB();
				if($conn !== NULL){
					$this->__logMySQL($funcion, $linea, $mensaje);
				}
			}

			if($typeLog==1 || $typeLog==3 ){
				$this->__logFile($funcion, $linea, $mensaje);
			}

		}

		private function __logFile($funcion, $linea, $mensaje)
		{
			error_log(date('Y-m-d H:i:s').": ".$funcion.":".$linea.":".$mensaje."\n", 3, $this->ini['pathLogs']."". $this->dia.'.log');
		}

		private function __logMySQL($funcion, $linea, $mensaje)
		{
			$sql="INSERT into log values(0,'$funcion', $linea, '$mensaje')";
			$result = $this->mysqli->query($sql);
		}

	}
?>