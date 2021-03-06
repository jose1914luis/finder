<?php
/*
	Clase encargada de la administración y gestión de empresas del SIGMIN
*/

	class Empresas {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Empresa.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  "
				select 
					id, 
					nit, 
					upper(nombre) as nombre,
					to_char(fecha_inicio_contrato,'DD/MM/YYYY') as fecha_inicio_contrato, 
					to_char(fecha_inicio_contrato::date  + (duracion_contrato_meses || ' month')::interval, 'DD/MM/YYYY') as fecha_fin_contrato,  
					case 
						when fecha_inicio_contrato::date  + (duracion_contrato_meses || ' month')::interval > now() then 'VIGENTE'
						else 'FINALIZADO' 
					end as contrato
				from empresas where fecha_inicio_contrato is not null order by nit			
			";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Empresas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectIdNameAll() {
			$queryStr =  'select id, nombre from empresas where fecha_inicio_contrato is not null order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Id y Nombre de Empresas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		
		function selectByNIT($nit) {	
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where nit=$1 limit 1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($nit));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el nit $nit.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByID($id) {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where id=$1 limit 1';			
			
			$result = pg_query_params($this->conn, $queryStr, array($id));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el Id $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function selectNameByID($id) {
			$queryStr =  'select nombre from empresas where id=$1 limit 1';			
			
			$result = pg_query_params($this->conn, $queryStr, array($id));
			if (!$result) {
			  echo "Error al consultar Nombre de la Empresa bajo el Id $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			if(!empty($lista[0]["nombre"])) return  $lista[0]["nombre"];
			else							return "";
		}		

		function selectByNombre($nombre) {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where nombre=$1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($nombre));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el Nombre $nombre.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($emp) {
			$queryStr =  "select empresas_insert($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13) as result";			
			
			$params = array(trim(utf8_encode($emp["emp_nit"])), trim(utf8_encode($emp["emp_nombre"])), trim($emp["emp_email"]), trim($emp["emp_telefono"]), trim(utf8_encode($emp["emp_direccion"])), $emp["emp_id_ciudad"],  $emp["emp_fecha_inicio_contrato"],  $emp["emp_duracion_contrato_meses"], $emp["rl_numero_documento"], $emp["rl_tipo_identificacion"], trim(utf8_encode($emp["rl_nombre"])), $emp["rl_email"], $emp["rl_celular"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];

		}		
	}	
?>

