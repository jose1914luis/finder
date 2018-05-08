<?php
/*
	Clase encargada de administrar la información relacionada con los documentos
	en el instante en que son indexados, es decir, el almacenamiento de los datos asociados
	a los indices y a las imagenes cargadas para los mismos.
*/
	//require_once("Modelos/ImagenesDocumentos.php"); // Librería para el tratamiento de las imagenes asociadas al documento		

	class DocumentosPlantillas {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase DocumentosPlantillas.\n";
				return 0;
			}
		}
		
		function getIdDocumentoPlantilla() {
			$queryStr =  "select nextval('keeper.doc_plantillas_seq') as result";
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al generar consecutivo de documentos_plantillas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["result"];
		}	
		
		function insertAll($documentoPlantilla, $IdSession, $rutaArchivo) {
			
			$estadoOperacion		= 0;
			$nombreArchivo 		= "$IdSession-{$documentoPlantilla["nroRadicado"]}.pdf";
			
			// Almacenamiento de las imagenes asociadas
			$rutaOrigen 		= $GLOBALS["docDigital"].$documentoPlantilla["codigoExpediente"]."/".$rutaArchivo;
			$estadoOperacion	= $this->saveImgFiles($_FILES, $documentoPlantilla["codigoExpediente"], $IdSession, $documentoPlantilla["nroRadicado"], $rutaOrigen); 
			
			if($estadoOperacion == "OK") {
				$idDocPlantilla 	= $this->getIdDocumentoPlantilla();
				$queryStr			= "select keeper.docplantillas_insert($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) as result";
				
				// id, id_empresa, id_plantilla, id_genera_documento, placa, numero_radicado, fecha_radicado, referencia, nombre_archivo, nro_folios
				$params 			= array($idDocPlantilla, $documentoPlantilla["idEmpresa"], $documentoPlantilla["idPlantilla"], $documentoPlantilla["solRequerimiento"], utf8_encode($documentoPlantilla["codigoExpediente"]), $documentoPlantilla["nroRadicado"], $documentoPlantilla["fechaRadicado"], utf8_encode($documentoPlantilla["docReferencia"]), $nombreArchivo, $documentoPlantilla["cantidadImagenes"]);
				
				
				$result = pg_query_params($this->conn, $queryStr, $params);
				$lista = pg_fetch_all($result);	
				$estadoOperacion = $lista[0]["result"];	
			}

			
			if($estadoOperacion == "OK") {
				// inserción de los indices digitalizados			
				foreach($documentoPlantilla as $indice=>$valor) {
					$campos = explode("_", $indice);
					if($campos[0]=="indice") {
						$queryStr = "select keeper.docplantillas_indices_insert($1, $2, $3) as result";
						if(!is_array($valor)) $valorCampo = $valor;
						else $valorCampo = implode(",",$valor);
						$params = array($idDocPlantilla, $campos[1], utf8_encode($valorCampo));
						$result = pg_query_params($this->conn, $queryStr, $params);
						$lista = pg_fetch_all($result);

						$estadoOperacion = $lista[0]["result"];
						if($estadoOperacion != "OK") break;
					}
				}
																
				// inserción de documentos asociados que generan uno o varios requerimientos
				if($estadoOperacion=="OK" && !empty($documentoPlantilla["tipoReq"])) {		
					$listaRequerimientos = $this->procesarRequerimientos($documentoPlantilla);
					$queryStr = "select keeper.docRequieren_insert($1, $2) as result";
					$params = array($idDocPlantilla, $listaRequerimientos);
					$result = pg_query_params($this->conn, $queryStr, $params);
					$lista = pg_fetch_all($result);			
					
					$estadoOperacion = $lista[0]["result"];
				}
				
				// inserción de documentos asociados que resuelven uno o varios requerimientos
				if($estadoOperacion=="OK" && !empty($documentoPlantilla["reqResuelve"])) {		
					$listaRespuestas = $this->procesarRespuestas($documentoPlantilla);
					$queryStr = "select keeper.docResuelven_insert($1, $2) as result";
					$params = array($idDocPlantilla, $listaRespuestas);
					$result = pg_query_params($this->conn, $queryStr, $params);
					$lista = pg_fetch_all($result);			
					
					$estadoOperacion = $lista[0]["result"];
				}
				
				// if($estadoOperacion != "OK")
					// return $operacion;

			} 	// else 
				//	return $lista[0]["result"];

			if($estadoOperacion != "OK") {
				// rollback de ingreso de datos
			} 	
			return $estadoOperacion; //$lista[0]["result"];						
		}
		
		function  procesarRequerimientos($requerimientos) {
			$coma = ",";
			$param[1] = $param[2] = $param[3] = $param[4] = $param[5] = "";
			
			for($i=1; $i<=sizeof($requerimientos["tipoReq"]); $i++) {
				if($i==sizeof($requerimientos["tipoReq"])) $coma="";
				if(!empty($requerimientos["tipoReq"][$i])) {
					$param[1] .=  '"'.$requerimientos["tipoReq"][$i].'"'.$coma;
					$param[2] .=  (!empty($requerimientos["recibeRequerimiento"][$i])) ? '"'.$requerimientos["recibeRequerimiento"][$i].'"'.$coma : '""'.$coma;
					$param[3] .=  (!empty($requerimientos["fechaReq"][$i])) ? '"'.$requerimientos["fechaReq"][$i].'"'.$coma : '""'.$coma;
					$param[4] .=  (!empty($requerimientos["fechaVence"][$i])) ? '"'.$requerimientos["fechaVence"][$i].'"'.$coma : '""'.$coma;
					$param[5] .=  (!empty($requerimientos["detalleReq"][$i])) ? '"'.str_replace('"', "´", $requerimientos["detalleReq"][$i]).'"'.$coma : '""'.$coma;
				}
			}			
			return "{{".$param[1]."},{".$param[2]."},{".$param[3]."},{".$param[4]."},{".utf8_encode($param[5])."}}"; 				
		}
		
		function  procesarRespuestas($respuestas) {
			$coma = ",";
			$param[1] = $param[2] = $param[3] = $param[4] = "";
					
			for($i=1; $i<=sizeof($respuestas["reqResuelve"]); $i++) {
				if($i==sizeof($respuestas["reqResuelve"])) $coma="";
				if(!empty($respuestas["reqResuelve"][$i])) {
					$param[1] .=  '"'.$respuestas["reqResuelve"][$i].'"'.$coma;
					$param[2] .=  (!empty($respuestas["fechaSol"][$i])) ? '"'.$respuestas["fechaSol"][$i].'"'.$coma : '""'.$coma;
					$param[3] .=  (!empty($respuestas["detalleSol"][$i])) ? '"'.str_replace('"', "´", $respuestas["detalleSol"][$i]).'"'.$coma : '""'.$coma;
					$param[4] .=  (!empty($respuestas["nivelSatisfaccion"][$i])) ? '"'.$respuestas["nivelSatisfaccion"][$i].'"'.$coma : '""'.$coma;					
				}
			}			
			return "{{".$param[1]."},{".$param[2]."},{".utf8_encode($param[3])."},{".$param[4]."}}"; 				
		}		

		function selectDocumentosRequieren($placa) {
			$queryStr =  "
				select 
					dr.id,
					dp.numero_radicado,
					to_char(dr.fecha_requerimiento, 'DD/MM/YYYY') as fecha_requerimiento,	  				
					to_char(dr.fecha_vencimiento, 'DD/MM/YYYY') as fecha_vencimiento,
					dr.detalle_requerimiento as referencia,
					coalesce(drs.estado_satisfaccion, 'Pendiente') as estado_resuelto	  				
				from keeper.documentos_plantillas dp
						inner join keeper.documentos_requieren dr on (dp.id=dr.id_documento_plantilla)
						left join keeper.documentos_resuelven drs on (dr.id=drs.id_documento_requiere)
						where dp.placa=$1					
				order by 2, 1	
			";
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al consultar documentos que requieren respuesta.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			
			pg_free_result($result);

			return  $lista;
		}

		function saveImgFiles($archivo, $codExpediente, $sessionId, $radicado, $rutaOrigen) { 
			$nombreArchivo = "$sessionId-$radicado.pdf";
			
			$creacionFolder = @mkdir($rutaOrigen, 0777, true);
			
			if (!copy($archivo["fileToUpload"]["tmp_name"],$rutaOrigen.$nombreArchivo)) {
				return "Error al guardar el archivo asociado al documento";	 
			}
			
			return "OK";
		}
		
		function getPathArchivoByNombre($nombreArchivo) {
			$queryStr =  "
				select dp.placa||'/'||replace(replace(p.ruta_plantilla,'-','/'),' ','_')||'/'||dp.nombre_archivo as result
				from keeper.documentos_plantillas dp 
					inner join keeper.plantillas p on (dp.id_plantilla=p.id)
				where nombre_archivo = $1 limit 1			
			";
			
			$result = pg_query_params($this->conn, $queryStr, array($nombreArchivo));
			if (!$result) {
			  echo "Error al acceder a getPathArchivoByNombre.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["result"];
		}		
		
	}	
?>

