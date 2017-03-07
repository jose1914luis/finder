<?php
/*
	Clase encargada de la administración y gestión de los chat de usuarios de SIGMIN
*/

	class Bitacora_Chats {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Bitacora_Chats.\n";
				return 0;
			}
		}
	
		function insertAll($chat) {
			$queryStr =  "Select bitacorachat_insert($1, $2, $3, $4, $5) as result";	

			$queryStr =  "
				insert into bitacora_chats (fecha, nombre_archivo, nombre_usuario, email, ip_cliente)	
					values ($1, $2, $3, $4, $5);			
			";
			
			$params = array("'".date("Y-m-d")."'",$chat["archivo"], trim(utf8_encode($chat["name"])), trim(utf8_encode($chat["email"])), $this->getClientIp());
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				return  "OK";
			}
		}
		
		function selectArchivosByFecha($fecha) {	
			$queryStr =  "select nombre_archivo, nombre_usuario from bitacora_chats where fecha = $1";

			$result = pg_query_params($this->conn, $queryStr, array($fecha));
			if (!$result) {
				echo "Error al consultar Usuario bajo el Documento $documento.\n";
				return 0;
			}					
	
			$lista = pg_fetch_all($result);	
			pg_free_result($result);
			
			return  $lista;
		}			

		function getClientIp() {
			$result = null;
			$ipSourceList = array(
				'HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED', 'REMOTE_ADDR'
			);
			foreach($ipSourceList as $ipSource) {
				if ( isset($_SERVER[$ipSource]) ) {
					$result = $_SERVER[$ipSource];
					break;
				}
			}
			return $result;
		}		
		
	}	
?>

