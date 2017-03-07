<?php
/*
	Clase encargada de la administración y gestión de los expedientes asociados a un usuario en SIGMIN
*/

	class ExpedientesUsuarios {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase ExpedientesUsuarios.\n";
				return 0;
			}
		}

		function insert($idUsuario, $placa, $proyecto) {
			$queryStr =  "select servicios.expedientes_usr_sgm_insertar($1,$2, $3) as result";			
			
			$params = array($idUsuario, $placa, $proyecto);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}
		
		function inactivarExpediente($idUsuario, $placa) {
			$queryStr =  "select servicios.expedientes_usr_sgm_inactivar($1,$2) as result";			
			
			$params = array($idUsuario, $placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}
		
		function selectByIdUser($idUsuario) {
			$queryStr =  "select placa, proyecto, tipo_expediente from servicios.expedientes_usuarios_sgm where id_usuario_sgm=$1 and estado='ACTIVO'";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al consultar expedientes por usuario";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
	}	
?>

