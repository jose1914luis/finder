<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes
	en el CMQ
*/

	class SeguimientosUsuarios {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase Usuarios.\n";
				return 0;
			}
		}
	
		function selectAll() {
			$queryStr =  'select * from keeper.seguimientos_usuarios order by fecha_accion';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Usuarios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function generarAccion($accion) {		
			$accion 		=   utf8_encode($accion);
			$queryStr		=   "insert into keeper.seguimientos_usuarios (login, accion, ruta_pagina, ip_equipo) values ($1, $2, $3, $4)";				
			$params 		= 	array($_SESSION['usuario_sgm'], $accion, $_SERVER['PHP_SELF'], $_SERVER['REMOTE_ADDR']);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR) 
				echo "<table bgcolor='red' border = 0><tr><td>Error al insertar accion del usuario '$accion': $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";						
		}		

	}	
?>

