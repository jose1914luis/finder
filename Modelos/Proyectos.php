<?php
/*
	Clase encargada de la administración y gestión de los proyectos asociados a las Empresas del SIGMIN
*/

	class Proyectos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Proyecto.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, id_empresa, id_titulo, codigo, nombre from proyectos order by codigo';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Proyectos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectByNIT($codigo) {	
			$queryStr =  'select id, id_empresa, id_titulo, codigo, nombre from proyectos where codigo=$1 limit 1 order by codigo';			
			
			$result = pg_query_params($this->conn, $queryStr, array($codigo));
			if (!$result) {
			  echo "Error al consultar Proyecto por C&oacute;digo $nit.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		
		function insertAll($pry, $idEmpresa) {
			$buscar = array("\r\n");
		
			$queryStr =  "select proyectos_insert($1, $2, $3, $4) as result";			
			
			$params = array($idEmpresa, trim(utf8_encode(str_replace($buscar,':',$pry["txtExpedientes"]))), trim(utf8_encode($pry["txtCodigoProyecto"])), trim(utf8_encode($pry["txtNombreProyecto"])));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		
		
		function getCoordsExpedienteByPlaca($placa) {
			$queryStr =  "select coalesce(get_CoordsExpedienteByPlaca($1), '') as result";			
			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		
	}	
?>

