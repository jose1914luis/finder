<?php
/*
	Clase encargada de la administración y gestión de los usuarios SGM o SIGMIN
*/

	class Usuarios_SGM {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Usuario.\n";
				return 0;
			}
		}

		function getNextId() {
			$queryStr =  "select nextval('usuarios_SGM_seq') as siguiente_id";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al obtener Id de Usuarios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	

			//print_r($lista);			

			return  $lista[0]["siguiente_id"];
		}	
		
		function selectAll() {
			$queryStr =  'select id, login, contrasenia, numero_documento, nombre, correo_electronico, telefono_oficina, numero_celular, estado, fecha_inicio, fecha_fin from usuarios_sgm order by login';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Usuarios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectByDocumento($documento) {	
			$queryStr =  "select id, login, contrasenia, numero_documento, nombre, correo_electronico, telefono_oficina, numero_celular, estado, fecha_inicio, fecha_fin from usuarios_sgm  where numero_documento=$1 and estado='ACTIVO'";			
			
			$result = pg_query_params($this->conn, $queryStr, array($nit));
			if (!$result) {
			  echo "Error al consultar Usuario bajo el Documento $documento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByID($id) {
			$queryStr =  "select id, login, contrasenia, numero_documento, nombre, correo_electronico, telefono_oficina, numero_celular, estado, fecha_inicio, fecha_fin from usuarios_sgm  where numero_documento=$1 and estado='ACTIVO' where id=$1 limit 1";			
			
			$result = pg_query_params($this->conn, $queryStr, array($id));
			if (!$result) {
			  echo "Error al consultar Usuario bajo el Id $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function selectByIDEmpresa($idEmpresa) {
			$queryStr =  "
				select us.id as id_usuario, pe.id_empresa, us.login, upper(nombre) as nombre_usuario
				from usuarios_sgm us inner join personal_empresas pe 
					on us.id = pe.id_usuario 
				where pe.id_empresa=$1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar Usuario por Empresa $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function insertAll($usr, $IdEmpresa) {
			$queryStr =  "Select usuariosgm_insert($1, $2, $3, $4, $5, $6, $7, $8) as result";			
			
			$params = array(trim(utf8_encode($usr["usr_login"])), md5(utf8_encode($usr["usr_contrasenia"])), trim($usr["usr_nro_documento"]), trim(utf8_encode($usr["usr_nombre"])), trim(utf8_encode($usr["usr_email"])), trim($usr["usr_tel_oficina"]), trim($usr["usr_celular"]), $IdEmpresa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

		function validaPasswd($login, $pwd) {
			$queryStr =  "
				select id_empresa
				from usuarios_sgm u inner join personal_empresas pe on (u.id=pe.id_usuario)
					where login=$1 and contrasenia=$2
				";			
			
			$params = array(trim(utf8_encode($login)), trim(md5(utf8_encode($pwd))));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!pg_last_error($this->conn)) {
				$lista = pg_fetch_all($result);	
				if(!empty($lista))
					return  $lista[0]["id_empresa"];
			} 
			return 0;	// Error durante el proceso de validación de usuario
		}	

		function validaAccesoPagina($login, $pwd, $accion="") {
			$queryStr =  "
				select id_empresa
				from usuarios_sgm u inner join personal_empresas pe on (u.id=pe.id_usuario)
					where login=$1 and contrasenia=$2
				";			
			
			$params = array(trim(utf8_encode($login)), trim(md5(utf8_encode($pwd))));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!pg_last_error($this->conn)) {
				$lista = pg_fetch_all($result);	
				if(!empty($lista))
					return  $lista[0]["id_empresa"];
			} 
			return 0;	// Error durante el proceso de validación de usuario
		}	
		
		
	}	
?>

