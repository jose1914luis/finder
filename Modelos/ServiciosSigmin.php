<?php
/*
	Clase encargada de la administración y gestión de los usuarios SGM o SIGMIN
*/

	class ServiciosSigmin {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase ServiciosSigmin.\n";
				return 0;
			}
		}

		function getServicios() {
			$queryStr =  "
				select id, nombre, precio 
				from servicios.tipos_servicios where estado='ACTIVO'	order by 1		
			";
			
			$result = pg_query($this->conn, $queryStr);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista;
			}
		}
		
		function habilitarServicio($email, $tipoServicio, $fechaIni, $fechaFin) {
			$queryStr =  "Select servicios.servicios_habilitar($1, $2, $3, $4) as result";			
			
			$params = array($email, $tipoServicio, $fechaIni, $fechaFin);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

		function validarAreaServicio($coordenadas, $rangoSuperior) {
			$queryStr =  "Select servicios.validar_poligono($1, $2) as result";			
			
			$params = array($coordenadas, $rangoSuperior);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	
		
		function getArea($coordenadas) {
			$queryStr =  "Select servicios.getArea($1) as result";			
			
			$params = array($coordenadas);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	
		
		function inhabilitarServicio($codAcceso) {
			$queryStr =  "Select servicios.servicios_inhabilitar($1) as result";			
			
			$params = array($codAcceso);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}		
		
		function validarServicio($email, $codAcceso) {
			$queryStr =  "Select servicios.servicios_validar($1, $2) as result";			
			
			$params = array($email, $codAcceso);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

		function getTipoServicio($codAcceso) {
			$queryStr =  "
				select ts.nombre as tipo_servicio from servicios.servicios_usuarios su inner join servicios.tipos_servicios ts on su.id_tipo_servicio=ts.id 
				where su.codigo_acceso=$1 
					and su.estado_servicio='ACTIVO'			
			";
			
			$params = array($codAcceso);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["tipo_servicio"];
			}
		}

		function getIDbyAcceso($codAcceso) {
			$queryStr =  "
				select id from servicios.servicios_usuarios where codigo_acceso=$1 limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($codAcceso));
			if (!$result) {
			  echo "Error al consultar login del codigo de acceso $codAcceso.\n";
			  return 0;
			}						
			$usr = pg_fetch_all($result);			
			pg_free_result($result);

			return  $usr[0]["id"];
		}	
		
		function getListaEmailsAlertas() {
			$queryStr =  "select servicios.get_alertas_archivadas() as emails";
	
			$result = pg_query($this->conn, $queryStr);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["emails"];
			}
		}
		
		function habilitarPromocion($email, $tipoServicio, $fechaIni, $fechaFin, $nombre) {
			$queryStr =  "Select servicios.insert_promocion($1, $2, $3, $4, $5) as result";			
			
			$params = array($email, $tipoServicio, $fechaIni, $fechaFin, $nombre);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}				
	}	
?>

