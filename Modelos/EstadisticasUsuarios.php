<?php
/*
	Clase encargada de la gestion de informaci�n y estadisticas de los usuarios externos
*/

	class EstadisticasUsuarios {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexi�n en la clase EstadisticasUsuarios.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal  from empresas order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Empresas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}			
		
		function getRealIP() {   
		   if( @$_SERVER['HTTP_X_FORWARDED_FOR'] != '' )   {
			  $client_ip =  ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "Unknown" );
		   
			  // los proxys van a�adiendo al final de esta cabecera
			  // las direcciones ip que van "ocultando". Para localizar la ip real
			  // del usuario se comienza a mirar por el principio hasta encontrar
			  // una direcci�n ip que no sea del rango privado. En caso de no
			  // encontrarse ninguna se toma como valor el REMOTE_ADDR
		   
			  $entries = preg_split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
		   
			  reset($entries);
			  while (list(, $entry) = each($entries))	  {
				 $entry = trim($entry);
				 if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )   {
					// http://www.faqs.org/rfcs/rfc1918.html
					$private_ip = array(
						  '/^0\./',
						  '/^127\.0\.0\.1/',
						  '/^192\.168\..*/',
						  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						  '/^10\..*/');
		   
					$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
		   
					if ($client_ip != $found_ip)	{   $client_ip = $found_ip; break;		}
				 }
			  }
		   }   else	   {
			  $client_ip = 	 ( !empty($_SERVER['REMOTE_ADDR']) ) ?	$_SERVER['REMOTE_ADDR']	:( ( !empty($_ENV['REMOTE_ADDR']) ) ?  $_ENV['REMOTE_ADDR'] :"Unknown" );
		   }  
		   return $client_ip;   
		}
		
		
		function setLogeoUsuario($loginUsuario) {
			// Obtener direcci�n IP del cliente
			// $ipAcceso = (@$_SERVER["HTTP_CLIENT_IP"]!="") ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
			$ipAcceso = $this->getRealIP();
			
			// Obtener browser del cliente y pais de consulta
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$paisConsulta = getCountryFromIP($ipAcceso, " NamE ");
						
			$queryStr =  "select setLogeoUsuario($1, $2, $3, $4) as result";			
			
			$params = array($loginUsuario, $ipAcceso, $browser, $paisConsulta);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function setConsultaUsuario($loginUsuario, $querySQL) {
			// Obtener direcci�n IP del cliente
			$ipAcceso = (@$_SERVER["HTTP_CLIENT_IP"]!="") ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
			
			$queryStr =  "select setConsultaUsuario($1, $2, $3) as result";						
			$params = array($loginUsuario, $ipAcceso, $querySQL);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}

		function setEstadisticasPlaca($loginUsuario, $placa) {
			// Obtener direcci�n IP del cliente
			$ipAcceso = (@$_SERVER["HTTP_CLIENT_IP"]!="") ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
			
			$queryStr =  "select setEstadisticasPlaca($1, $2, $3) as result";						
			$params = array($loginUsuario, $ipAcceso, $placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		

	}	
?>

