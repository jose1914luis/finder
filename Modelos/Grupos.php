<?php
/*
	Clase encargada de la administración y gestión de los Grupos generados por empresa en el SIGMIN
	Es decir, la relación de usuarios con los perfiles
*/

	class Grupos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Grupo.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, id_empresa, nombre from grupos where visualizar=1 order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Grupos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectByIdEmpresa($idEmpresa) {	
			$queryStr =  'select id, id_empresa, nombre from grupos where id_empresa=$1 and visualizar=1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar Grupos por Empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($grp, $idEmpresa) {
			$queryStr =  "select grupos_insert($1, $2, $3, $4) as result";			
			
			$params = array($idEmpresa, trim(utf8_encode($grp["txtNombreGrupo"])), $grp["selPerfil"], implode(":",$_POST["selUsuario"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}					
	}	
?>

