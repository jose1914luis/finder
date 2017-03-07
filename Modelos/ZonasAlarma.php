<?php
/*
	Clase encargada de la administración y gestión de las zonas de alarma del sistema SIGMIN
*/

	class ZonasAlarma {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase ZonasAlarma.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select gid_prospecto_sgm, emails_destinatarios, fecha_creacion, fecha_terminacion from zonas_alarma where habilitar=1 order by 1';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Alarma.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectByIdEmpresa($idEmpresa) {	
			$queryStr =  'select gid_prospecto_sgm, emails_destinatarios, fecha_creacion, fecha_terminacion from zonas_alarma where habilitar=1 and $idEmpresa = $1 order by 1';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar Alarmas por Empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($alarm) {
		
			$buscar = array("\r\n");
		
			$queryStr =  "select zonasalarma_insert($1, $2) as result";			
			$params = array($alarm["selProspecto"], trim(utf8_encode(str_replace($buscar,':',$alarm["listEmails"]))));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}					
	}	
?>

