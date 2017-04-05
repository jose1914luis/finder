<?php
/*
	Clase encargada de la administraci�n y gesti�n de los creditos de los usuarios SGM o SIGMIN
*/

	class CreditosUsuarios {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexi�n en la clase Usuario.\n";
				return 0;
			}
		}

		function getInfoCreditosByIdUsuario($idUsuario) {
			$queryStr =  "
				select us.nombre, coalesce(cu.credito, 0) as credito   
				from usuarios_sgm us
					left join servicios.creditos_usuarios cu on us.id=cu.id_usuario_sgm
				where us.id=$1			
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al obtener Info de Creditos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			return  $lista[0];
		}	
		
		function getCreditosHistoricosByIdUsuario($idUsuario) {
			$queryStr =  "
				select 
					cc.id as credito_prod,
					ps.nombre as producto,
					cc.creditos_consumidos,
					cc.placa,
					to_char(cc.fecha_registro, 'DD/MM/YYYY') as fecha_generacion,
					to_char(cc.fecha_vence, 'DD/MM/YYYY') as fecha_vence,
					ps.url_descarga,
					cc.tipo_expediente
				from usuarios_sgm us
					inner join servicios.creditos_usuarios cu on us.id=cu.id_usuario_sgm
					inner join servicios.creditos_consumidos cc on cu.id=cc.id_creditos_usr
					inner join servicios.productos_sgm ps on ps.id=cc.id_producto_sgm
				where us.id=$1 and fecha_vence >= now()
					order by cc.fecha_registro desc			
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al obtener Historico de Creditos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			return  $lista;
		}			
		
		function comprarCreditos($idUsuario, $creditos) {
			$queryStr =  "select servicios.comprar_creditos($1, $2) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, $creditos));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	
		
		function usarCreditos($idUsuario, $idProducto, $placa) {
			$queryStr =  "select servicios.usar_creditos($1, $2, $3) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, $idProducto, $placa));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}		
		
		function usarCreditosLista($idUsuario, $idProducto, $lista, $nroPoligonos) {
			$queryStr =  "select servicios.usar_creditos_lista($1, $2, $3, $4) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, $idProducto, $lista, $nroPoligonos));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}
		
		function validarVigenciaCredito($idCredito, $idUsuario) {
			$queryStr =  "select servicios.validar_vigencia_creditos($1, $2) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idCredito, $idUsuario));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}

		function costoCreditoByProducto($idProducto){
			$queryStr =  "
				select creditos from servicios.productos_sgm where id=$1 and estado='ACTIVO' limit 1	
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idProducto));
			if (!$result) {
			  echo "Error al obtener Historico de Creditos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			return  $lista[0]["creditos"];
		}
		
		function usarCreditosPromocion($idUsuario, $claveUso) {
			$queryStr =  "select servicios.consumir_creditos_promocion($1, $2) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, trim($claveUso)));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}

		function getIdCreditoConsumidoByPlacaYProd($idPlaca, $idProducto){
			$queryStr =  "
				select id as id_credito_consumido from servicios.creditos_consumidos where placa=$1 and id_producto_sgm=$2 and fecha_vence >= now()::date limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idPlaca, $idProducto));
			if (!$result) {
			  echo "Error al obtener credito ya consumido.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			return  $lista[0]["id_credito_consumido"];
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

		function validaLiberacionAreaUsuario($idUsuario){
			$queryStr =  "
				select count(1) as hay_credito_liberacion  
				from servicios.creditos_consumidos cc
					inner join servicios.creditos_usuarios cu on cc.id_creditos_usr=cu.id
				where
					cu.id_usuario_sgm = $1 and cc.fecha_vence::date >= now()::date
					and cc.id_producto_sgm in (1,2,3)				
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al validar existencia de servicio de reelase.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			return  $lista[0]["hay_credito_liberacion"];
		}
		
		function compraCreditosRelease($idUsuario, $idProducto) {
			$queryStr =  "select  servicios.usar_creditos_release($1, $2) 	as result"; 
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, $idProducto));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}			
		}
		
		function compraCreditosViewMap($idUsuario, $idProducto) {
			$queryStr =  "select  servicios.usar_creditos_viewmap($1, $2) 	as result"; 
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario, $idProducto));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}			
		}		
	}	


