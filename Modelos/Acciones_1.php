<?php
/*
	Clase encargada de la administración y gestión de las acciones (tareas que podran desempeñar los usuarios) configuradas para SIGMIN
*/

	class Acciones {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Acciones.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, id_modulo_sgm, nombre, descripcion, visualizar from acciones order by id_modulo, nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar las acciones.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectListAcciones() {	
			$queryStr =  '
				select ms.id as id_modulo, ms.nombre as modulo, ac.id as id_accion, ac.nombre as accion
				from acciones ac inner join modulos_sgm ms on (ms.id=ac.id_modulo_sgm)
					where visualizar=1
				order by ms.id, ac.id
			';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar las acciones (menu de selección).\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
	}	
?>

