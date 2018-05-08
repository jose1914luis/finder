<?php
/*
	Clase encargada de la administración y gestión de los perfiles generados por empresa en el SIGMIN
*/

	class Perfiles {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Perfil.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, id_empresa, nombre from perfiles where visualizar=1 order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Perfiles.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectByIdEmpresa($idEmpresa) {	
			$queryStr =  'select id, id_empresa, upper(nombre) as perfil from perfiles where id_empresa=$1 and visualizar=1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar Perfiles por Empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($prf, $idEmpresa) {
			$queryStr =  "select perfiles_insert($1, $2, $3) as result";			
			
			$params = array($idEmpresa, trim(utf8_encode($prf["txtNombrePerfil"])), implode(":",$_POST["rol"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}					
	}	
?>

