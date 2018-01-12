<?php
/*
	Clase encargada de la administraci�n y gesti�n de los usuarios SGM o SIGMIN
*/

	class Usuarios_SGM {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexi�n en la clase Usuario.\n";
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
			$queryStr =  "select id, login, contrasenia, numero_documento, nombre, correo_electronico, telefono_oficina, numero_celular, estado, fecha_inicio, fecha_fin from usuarios_sgm  where id=$1 and estado='ACTIVO' limit 1";			
			
			$result = pg_query_params($this->conn, $queryStr, array($id));
			if (!$result) {
			  echo "Error al consultar Usuario bajo el Id $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function getDocumentoByID($idUsuario) {
			$queryStr =  "select numero_documento from usuarios_sgm  where id=$1 limit 1";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al consultar documento por IdUsuario.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["numero_documento"];
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
		
		function getIDbyLogin($login) {
			$queryStr =  "
				select id from usuarios_sgm where login=$1 limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($login));
			if (!$result) {
			  echo "Error al consultar Usuario por el login $login.\n";
			  return 0;
			}						
			$usr = pg_fetch_all($result);			
			pg_free_result($result);

			return  $usr[0]["id"];
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
		
		function saveCaracterizacion($usr) {
			$razonSocial = $nombre = $apellido = "";
			// Validaci�n del tipo de documento. 5: juridica con NIT
			if($usr["selTipoDocumento"]!=5) 	{
				$nombre = utf8_encode($usr["txtNombre"]); 
				$apellido = utf8_encode($usr["txtApellido"]);
			} else								
				$razonSocial = utf8_encode($usr["txtNombre"]);
			
			//servicios.validar_caracterizacion_usrs('71796084','Jaime Andres','Moreno Toro','','jmoreno084@gmail.com','2101010','3002001000','calle 10#20-30',532,2)			
			$queryStr =  "Select servicios.validar_caracterizacion_usrs($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11) as result";			
			
			$params = array($usr["txtDocumento"], $nombre, $apellido, $razonSocial, $usr["buyerEmail"], $usr["txtTelefono"], $usr["txtCelular"], utf8_encode($usr["txtDireccion"]), $usr["selMunicipio"], $usr["selTipoDocumento"], $usr["txtFechaNacimiento"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}
		
		function getCaracterizacionByIdUsr($idUsuario) {	
			$queryStr =  "
				select  
					cu.id_tipo_documento, 
					us.numero_documento, 
					cu.nombres,
					cu.apellidos,
					cu.razon_social,
					us.correo_electronico,
					cu.telefono,
					cu.celular,
					cu.direccion,
					cu.id_municipio,
					td.nombre as tipo_documento,
					m.nombre as municipio,
					m.id_departamento,
					d.nombre as departamento,
					to_char(cu.fecha_nacimiento,'DD/MM/YYYY') as fecha_nacimiento
				from usuarios_sgm us
					left join servicios.caracterizacion_usuarios cu on cu.numero_documento=us.numero_documento
					left join tipos_documentos td on cu.id_tipo_documento = td.id
					left join municipios m on cu.id_municipio = m.id
					left join departamentos d on m.id_departamento = d.id
				where 	us.id=$1 limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al consultar Usuario bajo el Documento $documento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0];
		}

		function validarCaracterizacion($idUsuario) {
			$queryStr =  "
				select count(1) as existe_info  
				from usuarios_sgm us
					inner join servicios.caracterizacion_usuarios cu on us.numero_documento=cu.numero_documento
				where us.id=$1				
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al validar caracterizacion.\n";
			  return 0;
			}						
			$usr = pg_fetch_all($result);			
			pg_free_result($result);

			return  $usr[0]["existe_info"];
		}
		
		function insertUsrTmpAll($usr) {
			$queryStr =  "Select usrtmpweb_insert($1, $2, $3, $4) as result";			
			
			$params = array($usr["txtEmail"], $usr["txtDocumento"], $usr["txtNombre"], md5($usr["txtPassword"]));
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}		
		
		function getSerialbyEmail($email) {
			$queryStr =  "
				select serial from usuarios_temporales_web where correo_electronico=$1 limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($email));
			if (!$result) {
			  echo "Error al consultar Serial por el Email $email.\n";
			  return 0;
			}						
			$usr = pg_fetch_all($result);			
			pg_free_result($result);

			return  $usr[0]["serial"];
		}

		function habilitaUsrTmp($usr) {
			$queryStr =  "Select habusrtmp_insert($1, $2, $3) as result";			
			
			$params = array($usr["email"], $usr["identificacion"], $usr["codigo_verificacion"]);
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}
		
		function cambiarContraseniaUsuario($usr) {
			$queryStr =  "select usrUpdatePwd($1, $2, $3) as result";			
			
			$params = array($_SESSION['id_usuario'], $usr["claveOld"], $usr["claveNew"]);
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

		function asignarPwdAleatorio($usr) {
			$queryStr =  "select usrClearPwd($1, $2) as result";			
			
			$params = array($usr["login"], $usr["claveNew"]);
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

		function getLoginByEmail($email) {
			$queryStr =  "select login as result from usuarios_sgm where correo_electronico=$1";			
			
			$params = array($email);
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}		

		function getTipoCuentaSGM($idUsuario) {
			$queryStr =  "
				select case when empresa_usr_externo = 'USUARIO_TMP_WEB' then 'CUENTA DEMO' else 'CUENTA CORPORATIVA' end as result from usuarios_sgm where id=$1 limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al consultar Usuario por el login $login.\n";
			  return 0;
			}						
			$usr = pg_fetch_all($result);			
			pg_free_result($result);

			return  $usr[0]["result"];
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
			return 0;	// Error durante el proceso de validaci�n de usuario
		}	
		
		function validaPasswdMobile($login, $codigo) {
			$queryStr =  "
				select 
					md5(contrasenia||'-'||to_char(now(), 'YYYYMMDD')||'-'||login) as result 				
				from usuarios_sgm u 
				where 
					login=$1 and 
					md5(to_char(now(), 'YYYYMMDD')||'-'||login||'-'||contrasenia)=$2
				limit 1
			";			
			
			$params = array($login, $codigo);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	
			if(!empty($lista))
				return  $lista[0]["result"];
 
			return 0;	// Error durante el proceso de validaci�n de usuario
		}
		
		function validaAccesoPagina($login, $pwd, $accion="") {
			$queryStr =  "
				select id_empresa
				from usuarios_sgm u inner join personal_empresas pe on (u.id=pe.id_usuario)
					where login=$1 and contrasenia=$2 and u.ESTADO='ACTIVO' and pe.fecha_fin is null limit 1
				";			
			
			$params = array(trim(utf8_encode($login)), trim(md5(utf8_encode($pwd))));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!pg_last_error($this->conn)) {
				$lista = pg_fetch_all($result);	
				if(!empty($lista))
					return  $lista[0]["id_empresa"];
			} 
			return 0;	// Error durante el proceso de validaci�n de usuario
		}	
	}	
?>

