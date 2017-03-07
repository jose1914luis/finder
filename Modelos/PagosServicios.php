<?php
/*
	Clase encargada de la administración y gestión de los pagos por servicios ofrecidos
*/

	class PagosServicios {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Pagos_Servicios.\n";
				return 0;
			}
		}
	
		function insertarConsignacion($regConsignacion) {
			$queryStr =  "
				select servicios.consignaciones_insertar($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11) as result
			";
			
			$params = array($regConsignacion["varReferenceCode"],$regConsignacion["varTipoServicio"], $regConsignacion["varAmount"], $regConsignacion["varDocumento"], $regConsignacion["varNombre"], $regConsignacion["varApellido"], $regConsignacion["varEmail"], $regConsignacion["varTelefono"], $regConsignacion["varDireccion"], $regConsignacion["varTipoDocumento"], $regConsignacion["varMunicipio"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function monitorearFacturaEbill($nroTransaccion, $facturaEbill, $observacion) {
			$queryStr =  "
				select servicios.setFacturaEbill($1, $2, $3) as result
			";
			
			$params = array($nroTransaccion, $facturaEbill, $observacion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function sumarMesesAlertaLiberacion($valor) {
			if($valor>=260000) 			$meses = 12;
			else if ($valor>=150000)	$meses = 6;
			else 						$meses = 1;
			
			$fecha  	= date('Y-m-j');
			$nuevafecha = strtotime ( "+$meses month" , strtotime ( $fecha ) ) ;
			return date ( 'Y-m-j' , $nuevafecha );			
		}
		
		function getTipoServicioByNroTransaccion($nroTransaccion) {
			$queryStr =  "select id_tipo_servicio as result from servicios.consignaciones where numero_transaccion=$1";			
			$params = array($nroTransaccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		

		function habilitarConsignacion($nroTransaccion, $valor) {
			$queryStr =  "select servicios.servicios_habilitar($1, $2) as result";
			
			$fechaFin = null;
			$idTipoServ = $this->getTipoServicioByNroTransaccion($nroTransaccion);
			if($idTipoServ >= 1 && $idTipoServ <= 3) $fechaFin = $this->sumarMesesAlertaLiberacion($valor); 
			
			$params = array($nroTransaccion, $fechaFin);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function getConsignacionByNroTransaccion($nroTransaccion) {
			$queryStr =  "
				select 
					sc.id_tipo_servicio,
					tp.nombre as servicio,
					sc.nombre || ' ' || sc.apellido as nombres,
					sc.correo_electronico, 
					sc.codigo_acceso
				from servicios.consignaciones sc inner join servicios.tipos_servicios tp on sc.id_tipo_servicio=tp.id
				where sc.numero_transaccion=$1 and sc.estado_transaccion=1 limit 1			
			";
				
			$params = array($nroTransaccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error en getConsignacionByNroTransaccion.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0];			
		}
		
		function getIdUsuarioByConsignacion($nroTransaccion) {
			$queryStr =  "
				select us.id as id_usuario, round(sc.valor) as precio
				from servicios.consignaciones sc 
					inner join usuarios_sgm us on sc.numero_documento=us.numero_documento
				where numero_transaccion=$1					
			";
				
			$params = array($nroTransaccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error en getIdUsuarioByConsignacion.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0];			
		}		
		
		function getConsignacionToEbillPay($nroTransaccion) {
			$queryStr =  "
				select 
					sc.numero_transaccion,
					sc.id_tipo_servicio,
					tp.nombre as servicio,
					sc.valor,
					sc.numero_documento,
					sc.nombre || ' ' || sc.apellido as nombres,	
					sc.correo_electronico,
					sc.codigo_acceso,
					sc.direccion,
					sc.telefono,
					m.nombre as municipio,
					d.nombre as departamento,
					d.codigo_dane||m.codigo_dane as mpio_dane,
					to_char(sc.fecha_proceso ,'YYYY-MM-DD')||'T'||to_char(sc.fecha_proceso ,'HH24:MI:SS')||'-05:00' as fecha_proceso,
					to_char(sc.fecha_proceso, 'YYYY') as anio,
					round(valor/1.16) as valor_parcial,
					valor - round(valor/1.16) as valor_iva,
					td.id as id_tipo_documento,
					tp.codigo
				from servicios.consignaciones sc 
					inner join servicios.tipos_servicios tp on sc.id_tipo_servicio=tp.id
					left join tipos_documentos td on sc.id_tipo_documento=td.id
					left join municipios m on sc.id_municipio=m.id
					left join departamentos d on m.id_departamento=d.id
				   where sc.numero_transaccion=$1 and sc.estado_transaccion=1 limit 1			
			";
				
			$params = array($nroTransaccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error en getConsignacionToEbillPay.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0];			
		}
		
		function getUsuarioByCodAcceso($codAcceso) {
			$queryStr =  "
				select 
					sc.id as id_pago,
					sc.correo_electronico as username,
					sc.id_tipo_servicio,
					tp.nombre as servicio,
					sc.codigo_acceso as password,
					tp.rango_inferior,					
					tp.rango_superior
				from servicios.consignaciones sc 
					inner join servicios.tipos_servicios tp on sc.id_tipo_servicio=tp.id
				where sc.codigo_acceso=$1 and sc.estado_transaccion=1 limit 1			
			";
				
			$params = array($codAcceso);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error en getUsuarioByCodAcceso.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0];			
		}	
		
		function insertPago($pagoOnline) {					
			$params = array (
				$pagoOnline["merchant_id"],											
				$pagoOnline["state_pol"],
				$pagoOnline["risk"],
				$pagoOnline["response_code_pol"],
				$pagoOnline["reference_sale"],
				$pagoOnline["reference_pol"],
				$pagoOnline["sign"],
				$pagoOnline["extra1"],
				$pagoOnline["extra2"],
				$pagoOnline["payment_method"],
				$pagoOnline["payment_method_type"],
				$pagoOnline["installments_number"],
				$pagoOnline["value"],
				$pagoOnline["tax"],
				$pagoOnline["additional_value"],
				$pagoOnline["transaction_date"],
				$pagoOnline["currency"],
				$pagoOnline["email_buyer"],
				$pagoOnline["cus"],
				$pagoOnline["pse_bank"],
				$pagoOnline["test"],
				$pagoOnline["description"],
				$pagoOnline["billing_address"],
				$pagoOnline["shipping_address"],
				$pagoOnline["phone"],
				$pagoOnline["office_phone"],
				'No Definido',//$pagoOnline["account_number_ach"],
				'No Definido',//$pagoOnline["account_type_ach"],
				$pagoOnline["administrative_fee"],
				$pagoOnline["administrative_fee_base"],
				$pagoOnline["administrative_fee_tax"],
				$pagoOnline["airline_code"],
				$pagoOnline["attempts"],
				$pagoOnline["authorization_code"],
				$pagoOnline["bank_id"],
				$pagoOnline["billing_city"],
				$pagoOnline["billing_country"],
				$pagoOnline["commision_pol"],
				$pagoOnline["commision_pol_currency"],
				$pagoOnline["customer_number"],
				$pagoOnline["date"],
				$pagoOnline["error_code_bank"],
				$pagoOnline["error_message_bank"],
				$pagoOnline["exchange_rate"],
				$pagoOnline["ip"],
				$pagoOnline["nickname_buyer"],
				$pagoOnline["nickname_seller"],
				$pagoOnline["payment_method_id"],
				$pagoOnline["payment_request_state"],
				$pagoOnline["pse_reference1"],
				$pagoOnline["pse_reference2"],
				$pagoOnline["pse_reference3"],
				$pagoOnline["response_message_pol"],
				$pagoOnline["shipping_city"],
				$pagoOnline["shipping_country"],
				$pagoOnline["transaction_bank_id"],
				$pagoOnline["transaction_id"],
				$pagoOnline["payment_method_name"]
			);	
					
			$i=0;
			
			$queryStr =  "
				select servicios.valida_pago_insert($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22,$23,$24,$25,$26,$27,$28,$29,$30,$31,$32,$33,$34,$35,$36,$37,$38,$39,$40,$41,$42,$43,$44,$45,$46,$47,$48,$49,$50,$51,$52,$53,$54,$55,$56,$57,$58) as result					
			";
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}	

		function getPagos($fechaIni, $fechaFin) {
			if($fechaIni=="") $fechaIni = "1/1/2016";
			if($fechaFin=="") $fechaFin = date("d/m/Y");
			
			$queryStr =  "
				select 
					numero_transaccion,
					ts.nombre as tipo_servicio,
					valor as costo_servicio,
					td.nombre as tipo_documento,
					numero_documento,
					trim(sc.nombre || ' ' || sc.apellido) as nombre,
					correo_electronico,
					telefono,
					m.nombre as ciudad,
					direccion,
					fecha_proceso,
					case 
						when estado_transaccion=0 then 'SIN PAGO'
						when estado_transaccion=1 then '<div style=\"background-color: green;\"><b><font color=\"white\">PAGADO</font></b></div>'
						else '<div style=\"background-color: green;\"><b><font color=\"white\">Error en la Transacci&oacute;n</font></b></div>'
					end as estado_transaccion,
					fecha_vence
				from servicios.consignaciones sc
					inner join servicios.tipos_servicios ts on sc.id_tipo_servicio = ts.id
					left join municipios m on sc.id_municipio=m.id
					left join tipos_documentos td on sc.id_tipo_documento=td.id
				 where fecha_proceso between to_date($1,'dd/mm/yyyy') and to_date($2,'dd/mm/yyyy')+1
				order by fecha_proceso desc
			";
			
			$result = pg_query_params($this->conn, $queryStr, array($fechaIni, $fechaFin));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta de pagos
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista;
			}
		}
		
	}	
?>

