<?php
/*
	Clase encargada de la administración y gestión de documentos del sistema SIGMIN
*/

	class DocumentManagement {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase DocumentManagement.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  '';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar todos los documentos digitalizados.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectFormsByPlaca($placa) {	
			$queryStr =  "
				select distinct
					ds.fecha_radicado::date,
					su.id as id_formulario,
					su.nombre as formulario,
					ds.id as id_documento,
					ds.referencia,
					ds.nombre_archivo as nombre_pdf					
				from keeper.plantillas su 
					inner join keeper.documentos_plantillas ds on (su.id=ds.id_plantilla)
				where ds.placa = upper($1)
				order by ds.fecha_radicado::date, su.id									
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al consultar Formularios por Expediente.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
			
		
		function selectExpedientesByEmpresa($idEmpresa) {	
			$queryStr =  '
				select distinct ex.placa as expediente from keeper.expedientes ex where id_empresa=$1 order by ex.placa							
			';	

			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar los Expedientes Digitales de la empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function selectFormsByIdDocumento($idDocumento) {	
			$queryStr =  '
				select distinct
					su.id as id_formulario,
					su.nombre as formulario,
					ds.referencia,
					ds.id as id_documento,
					ds.nombre_archivo as nombre_pdf
				from keeper.plantillas su 
					inner join keeper.documentos_plantillas ds on (su.id=ds.id_plantilla)
				where ds.id = $1
				order by su.id							
			';	

			$result = pg_query_params($this->conn, $queryStr, array($idDocumento));
			if (!$result) {
			  echo "Error al consultar Formularios por Id de Documento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectTextDocumentByFormDocument($idDocumento) 	{	
			$queryStr =  '
				select distinct
					su.nombre as formulario,
					ds.placa,
					i.posicion as nro,
					i.nombre as indice,
					td.nombre as tipo_dato,
					keeper.docplantillas_indices_select_data(ds.id, i.id, i.id_tipo_dato) as dato
				from keeper.plantillas su 
					inner join keeper.documentos_plantillas ds on (su.id=ds.id_plantilla)
					inner join keeper.indices i on (i.id_plantilla=su.id)
					inner join keeper.tipos_datos td on (i.id_tipo_dato=td.id)
				where ds.id = $1
				order by i.posicion							
			';	

			$result = pg_query_params($this->conn, $queryStr, array($idDocumento));
			if (!$result) {
			  echo "Error al consultar datos por formulario e idDocumento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	

		function selectDocPlantillaByFormDocument($idDocumento) 	{	
			$queryStr =  '
				select 
					ad.nombre as genera_documento,
					dp.placa,
					dp.numero_radicado,
					dp.fecha_radicado, 
					dp.referencia,
					dp.nro_folios as numero_folios,
					dp.nombre_archivo
				from keeper.documentos_plantillas dp
					inner join keeper.actores_documentos ad on (dp.id_genera_documento=ad.id)
				where dp.id=$1 limit 1			
			';	

			$result = pg_query_params($this->conn, $queryStr, array($idDocumento));
			if (!$result) {
			  echo "Error al consultar datos por formulario e idDocumento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  @$lista[0];
		}	
		
		function selectDocRequierenByFormDocument($idDocumento) 	{	
			$queryStr =  "
				select  
					drq.id_documento_plantilla as id_doc_requiere,
					tr.nombre as tipo_requerimiento,
					drq.fecha_requerimiento,
					drq.fecha_vencimiento,
					drq.detalle_requerimiento as descripcion,
					ad.nombre as requerido_a,
					coalesce(dp2.numero_radicado, 'NO RESUELTO')  as resuelto_por,
					coalesce(dre.estado_satisfaccion, 'NO DEFINIDO') as estado_satisfaccion,
					dp2.id as id_doc_resuelve
				from keeper.documentos_requieren drq
					inner join keeper.tipos_requerimientos tr on (drq.id_tipo_requerimiento=tr.id)
					inner join keeper.actores_documentos ad on (drq.id_recibe_requerimiento=ad.id)
					left join keeper.documentos_resuelven dre on (drq.id=dre.id_documento_requiere)
					left join keeper.documentos_plantillas dp2 on (dre.id_documento_plantilla=dp2.id)
				where drq.id_documento_plantilla=$1
				order by fecha_requerimiento::date
			";	

			$result = pg_query_params($this->conn, $queryStr, array($idDocumento));
			if (!$result) {
			  echo "Error al consultar datos por formulario e idDocumento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function selectDocResuelvenByFormDocument($idDocumento) 	{	
			$queryStr =  '
				select  
					drq.id_documento_plantilla as id_doc_requiere,
					dp2.numero_radicado as radicado_requiere,
					dre.fecha_cumplimiento,
					dre.detalle_cumplimiento,
					dre.estado_satisfaccion
				from keeper.documentos_resuelven dre
					inner join keeper.documentos_requieren drq on (dre.id_documento_requiere=drq.id)
					inner join keeper.documentos_plantillas dp2 on (drq.id_documento_plantilla=dp2.id)
				where dre.id_documento_plantilla=$1
				order by dre.fecha_cumplimiento::date
			';	

			$result = pg_query_params($this->conn, $queryStr, array($idDocumento));
			if (!$result) {
			  echo "Error al consultar datos por formulario e idDocumento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectChronologyByPlacaEmpresa($placa, $idEmpresa) {
			$queryStr =  "
				select
					ds.id as id_documento,
					docPlantilla_orderByFecha(ds.id) as fecha_orden, 
					to_char(docPlantilla_orderByFecha(ds.id),'dd/mm/YYYY') as fecha_imagen,
					sd.nombre,
					ds.referencia
				from plantillas sd inner join documentos_plantillas ds on (sd.id=ds.id_plantilla)
				where ds.placa=upper($1) and id_empresa=$2
				order by 2						
			";	

			$result = pg_query_params($this->conn, $queryStr, array($placa, $idEmpresa));
			if (!$result) {
			  echo "Error al consultar Cronologia.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectAlertByPlacaEmpresa($placa, $idEmpresa) {
			$queryExpediente = "";
			if(!empty($placa)) $queryExpediente = "and ds.placa=$2";
		
			$queryStr =  "
				select
					ds.placa as expediente,	
					ds.id as id_doc_requiere,
					ds.id_doc_resuelve,
					ds.fecha_inicio_termino as fecha_orden_requiere,
					ds.fecha_cumple_termino as fecha_orden_resuelve,
					to_char(ds.fecha_inicio_termino,'dd/mm/YYYY') as fecha_imagen_requiere,
					to_char(ds.fecha_cumple_termino,'dd/mm/YYYY') as fecha_imagen_resuelve,
					su.nombre as nombre_requiere,
					su2.nombre as nombre_resuelve,
					to_char(ds.fecha_fin_termino,'dd/mm/YYYY') as fecha_expiracion,
					(ds.fecha_fin_termino-ds.fecha_cumple_termino) as difference,	
					case
						when (ds.fecha_fin_termino-ds.fecha_cumple_termino) > '0 days' then 'Pending...'
						else 'Expired'
					end as status
				from 	documentos_plantillas ds
					inner join plantillas su on (ds.id_plantilla=su.id)
					inner join documentos_plantillas ds2 on (ds.id_doc_resuelve=ds2.id)
					inner join plantillas su2 on (ds2.id_plantilla=su2.id)	
				where 	ds.id_empresa=$1
						$queryExpediente					
				order by 1, 4				
			";	

			if(!empty($placa)) 	$result = pg_query_params($this->conn, $queryStr, array($idEmpresa, $placa));
			else 				$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			
			
			if (!$result) {
			  echo "Error al consultar Alertas por placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectHistoryByPlacaEmpresa($placa, $idEmpresa) {
		
			$queryStr =  "select * from keeper.v_historial_expediente	where placa=$1 and id_empresa=$2	order by fecha_documento::date, fecha_inicio_termino::date";	

			$result = pg_query_params($this->conn, $queryStr, array($placa, $idEmpresa));
			
			if (!$result) {
			  echo "Error al consultar Hist&oacute;ricos por placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function selectAllAlerts($idEmpresa) {
		
			$queryStr =  "
			select  
				placa,
				documento,
				referencia_documento,
				genera_documento,
				recibe_requerimiento,
				fecha_documento,
				fecha_inicio_termino,
				fecha_cumple_termino,
				fecha_vence_termino,
				documento_resuelve,
				referencia_resuelve,
				diff,
				estado_requerimiento,
				tiempo_vencimiento,
				id_empresa, idfrm	
			from keeper.v_historial_expediente dp
				where dp.id_empresa=$1	and  dp.fecha_cumple_termino is null and dp.fecha_vence_termino is not null	
			order by fecha_documento	
			/*
				select
					dp.placa,
					p.nombre as documento,
					dp.referencia as referencia_documento,
					dp.fecha_documento,
					sr.nombre as clasificacion_requerimiento,
					to_char(dp.fecha_documento, 'DD/MM/YYYY HH24:MI:SS') as fecha_documento,
					to_char(dp.fecha_inicio_termino, 'DD/MM/YYYY HH24:MI:SS') as fecha_inicio_termino,
					to_char(dp.fecha_cumple_termino, 'DD/MM/YYYY HH24:MI:SS') as fecha_cumple_termino,
					to_char(dp.fecha_fin_termino, 'DD/MM/YYYY HH24:MI:SS') as fecha_vence_termino,
					(dp.fecha_fin_termino - dp.fecha_cumple_termino) as diff,
					case
						when dp.fecha_fin_termino > now() then 'Pending...'
						when dp.fecha_fin_termino < now() then 'Expired'
						else null
					end as estado_requerimiento,
					case 
						when  dp.fecha_cumple_termino is null and dp.fecha_fin_termino is not null then (dp.fecha_fin_termino - now())
						else null
					end as tiempo_vencimiento,
					dp.id_empresa,
					dp.id as idfrm
				from documentos_plantillas dp
					inner join plantillas p on (dp.id_plantilla=p.id)
					inner join solicitantes_requerimientos sr on (dp.id_sol_requerimiento=sr.id)	
				where dp.id_empresa=$1
					and  dp.fecha_cumple_termino is null and dp.fecha_fin_termino is not null					
				order by 4
			*/	
			";	

			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			
			if (!$result) {
			  echo "Error al consultar Hist&oacute;ricos por placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function getImageIcon($formulario) {
			$icono = "otros_icon.gif";
			$cadena = strtoupper($formulario);
			
			if(strpos($cadena, "RADICACION")!==FALSE) 				
				$icono = "radicacion_icon.gif";
			else if(strpos($cadena, "INFORMA")!==FALSE) 			
				$icono = "informa_icon.gif";
			else if(strpos($cadena, "ESTUDIO")!==FALSE&&(strpos($cadena, "TECNICO")!==FALSE||strpos($cadena, "JURIDICO")!==FALSE)) 			
				$icono = "estudioTJ_icon.gif";
			else if(strpos($cadena, "CONTRATO")!==FALSE) 			
				$icono = "contrato_icon.gif";
			else if(strpos($cadena, "ANOTACION")!==FALSE) 			
				$icono = "rmn_icon.gif";
			else if(strpos($cadena, "OFICIO")!==FALSE) 				
				$icono = "oficio_icon.gif";
			else if(strpos($cadena, "REQUERIMIENTO")!==FALSE) 			
				$icono = "requiere_icon.gif";
			else if(strpos($cadena, "NOTIFICACION")!==FALSE) 			
				$icono = "notifica_icon.gif";
			else if(strpos($cadena, "CANON")!==FALSE) 			
				$icono = "canon_icon.gif";
			else if(strpos($cadena, "REGALIA")!==FALSE) 			
				$icono = "regalias_icon.gif";
			else if(strpos($cadena, "PTI")!==FALSE||strpos($cadena, "PTO")!==FALSE) 			
				$icono = "pti_pto_icon.gif";
			else if(strpos($cadena, "VISITA")!==FALSE) 			
				$icono = "visita_icon.gif";
			else if(strpos($cadena, "POLIZA")!==FALSE&&strpos($cadena, "AMBIENTAL")!==FALSE) 			
				$icono = "poliza.gif";
			else if(strpos($cadena, "LICENCIA")!==FALSE&&strpos($cadena, "AMBIENTAL")!==FALSE) 			
				$icono = "licencia_icon.gif";
			else if((strpos($cadena, "FORMATO")!==FALSE&&strpos($cadena, "BASICO")!==FALSE)||strpos($cadena, "FBM")!==FALSE) 			
				$icono = "fbm_icon.gif";
			else if(strpos($cadena, "ARCHIVA")!==FALSE||strpos($cadena, "CANCELA")!==FALSE) 			
				$icono = "archivo_icon.gif";
			else if(strpos($cadena, "MULTA")!==FALSE) 			
				$icono = "multas_icon.gif";
			else if(strpos($cadena, "RESOLUCION")!==FALSE) 			
				$icono = "resolucion_icon.gif";
			else if(strpos($cadena, "AUTO")!==FALSE) 			
				$icono = "auto_icon.gif";
				
			return $icono;	
		
		}
							
	}	
?>

