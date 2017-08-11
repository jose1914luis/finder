<?php
/*
	Clase encargada de administrar la informaci�n relacionada a consultas sobre solicitudes y titulos
	en el CMQ
*/

	class ReportGenerator {	
		var $conn;
		var $idSolicitud;
		var $criterios;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexi�n con enlaces superiores.\n";
				return 0;
			}
		}
			
			
		function getModalidades() {

			$queryStr = "select modalidad from get_modalidades";
			
			$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al generar listado de modalidades: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista)) return null;	
			return  $lista;		
		}	

		function getEstadosJuridicos() {

			$queryStr = "select estado_juridico from get_estados_juridicos";
			
			$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al generar listado de estados juridicos: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista)) return null;	
			return  $lista;		
		}	
		
		function selectSolicitudesConsultas($codExpediente, $mineral, $municipio, $departamento, $titular, $radicaDesde, $radicaHasta, $modalidad, $estadoJuridico) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$mineral 		= utf8_encode(strtoupper($mineral)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			$titular 		= utf8_encode(strtoupper($titular));
			$modalidad		= utf8_encode(strtoupper($modalidad));
			
			$where = "";
			$posVar =1;

			$criteriosConsulta = "";
		
			if(!empty($codExpediente)) {
				$where .= "placa like $".($posVar++)." and ";
				$parametros[0] = "%".strtoupper($codExpediente)."%";
				$criteriosConsulta .= "Placa contiene '".$codExpediente."' & ";
			}

			if($modalidad != "ALL") {
				$where .= "modalidad = $".($posVar++)." and ";
				$parametros[1] = strtoupper($modalidad);
				$criteriosConsulta .= " modalidad igual a ".$modalidad." & ";
			}

			if($estadoJuridico != "ALL") {
				$where .= "estado_juridico = $".($posVar++)." and ";
				$parametros[2] = strtoupper($estadoJuridico);
				$criteriosConsulta .= " estado juridico igual a ".$estadoJuridico." & ";
			}
			
			if(!empty($radicaDesde)) {
				$where .= "fecha_radicacion >= to_timestamp( $".($posVar++).",'dd/mm/yyyy') and ";
				$parametros[3] = strtoupper($radicaDesde);
				$criteriosConsulta .= " fecha de radicaci&oacute;n desde: ".$radicaDesde." & ";
			}			

			if(!empty($radicaHasta)) {
				$where .= "fecha_radicacion < to_timestamp( $".($posVar++).",'dd/mm/yyyy')::date + 1 and ";
				$parametros[4] = strtoupper($radicaHasta);
				$criteriosConsulta .= " fecha de radicaci&oacute;n hasta: ".$radicaHasta." & ";
			}		
			
			if(!empty($mineral)) {
				$where .= "minerales like $".($posVar++)." and ";
				$parametros[5] = "%".strtoupper($mineral)."%";
				$criteriosConsulta .= " minerales contiene '".$mineral."' & ";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[6] = "%".strtoupper($municipio)."%";
				$criteriosConsulta .= " municipio contiene '".$municipio."' & ";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[7] = "%".strtoupper($departamento)."%";
				$criteriosConsulta .= " departamento contiene '".$departamento."' & ";
			}

			if(!empty($titular)) {
				$where .= "solicitantes like $".($posVar++)." and ";
				$parametros[8] = "%".strtoupper($titular)."%";
				$criteriosConsulta .= " titular contiene '".$titular."' & ";
			}
			
			if($criteriosConsulta=="")
				$this->criterios = " NINGUNO ";
			else
				$this->criterios = $criteriosConsulta;

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa,
					modalidad,
					estado_juridico,
					grupo_trabajo,
					formulario,
					fecha_radicacion,
					fecha_terminacion,
					fecha_otorgamiento,
					fecha_creacion,
					area_solicitada_ha,
					area_definitiva_ha,
					direccion_correspondencia,
					telefono_contacto,
					observacion,
					justificacion_extemporanea,
					municipios,
					solicitantes,
					minerales,
					plancha_igac,
					sistema_origen,
					centroide,
					descripcion_pa
				from v_solicitudes $where					
			";		
					
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Solicitudes. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectTitulosConsultas($codExpediente, $mineral, $municipio, $departamento, $titular, $otorgaDesde, $otorgaHasta, $modalidad, $estadoJuridico) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$mineral 		= utf8_encode(strtoupper($mineral)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			$titular 		= utf8_encode(strtoupper($titular));
			$modalidad		= utf8_encode(strtoupper($modalidad));
	
			$where = "";
			$posVar =1;
			$criteriosConsulta = "";

		
			if(!empty($codExpediente)) {
				$where .= "(placa like $".($posVar)." or codigo_rmn like $".($posVar)." or codigo_anterior like $".($posVar).") and ";
				$posVar++;
				$parametros[0] = "%".strtoupper($codExpediente)."%";
			}
			
			if($modalidad != "ALL") {
				$where .= "modalidad = $".($posVar++)." and ";
				$parametros[1] = strtoupper($modalidad);
				$criteriosConsulta .= " modalidad igual a ".$modalidad." & ";
			}

			if($estadoJuridico != "ALL") {
				$where .= "estado_juridico = $".($posVar++)." and ";
				$parametros[2] = strtoupper($estadoJuridico);
				$criteriosConsulta .= " estado juridico igual a ".$estadoJuridico." & ";
			}
			
			if(!empty($otorgaDesde)) {
				$where .= "fecha_inscripcion >= to_timestamp( $".($posVar++).",'dd/mm/yyyy') and ";
				$parametros[3] = strtoupper($otorgaDesde);
				$criteriosConsulta .= " fecha de inscripci&oacute;n en RMN desde: ".$otorgaDesde." & ";
			}			

			if(!empty($otorgaHasta)) {
				$where .= "fecha_radicacion < to_timestamp( $".($posVar++).",'dd/mm/yyyy')::date + 1 and ";
				$parametros[4] = strtoupper($otorgaHasta);
				$criteriosConsulta .= " fecha de inscripci&oacute;n en RMN hasta: ".$otorgaHasta." & ";
			}				

			if(!empty($mineral)) {
				$where .= "minerales like $".($posVar++)." and ";
				$parametros[5] = "%".strtoupper($mineral)."%";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[6] = "%".strtoupper($municipio)."%";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[7] = "%".strtoupper($departamento)."%";
			}

			if(!empty($titular)) {
				$where .= "titulares like $".($posVar++)." and ";
				$parametros[8] = "%".strtoupper($titular)."%";
			}

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa, 
					codigo_rmn, 
					codigo_anterior, 
					modalidad, 
					estado_juridico, 
					grupo_trabajo, 
					fecha_inscripcion, 
					fecha_contrato, 
					fecha_terminacion, 
					fecha_creacion, 
					area_otorgada_ha, 
					area_definitiva_ha, 
					direccion_correspondencia, 
					telefono_contacto, 
					municipios, 
					titulares, 
					minerales, 
					plancha_igac, 
					sistema_origen, 
					descripcion_pa, 
					centroide
				from v_titulos $where					
			";		
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);
	
			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Titulos. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectProspectosConsultas($codExpediente, $municipio, $departamento) {
			$codExpediente = utf8_encode(strtoupper($codExpediente));
			$municipio = utf8_encode(strtoupper($municipio));
			$departamento = utf8_encode(strtoupper($departamento));
		
			$where = "";
			$posVar =1;
		
			if(!empty($codExpediente)) {
				$where .= "placa like $".($posVar++)." and ";
				$parametros[0] = "%".strtoupper($codExpediente)."%";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[1] = "%".strtoupper($municipio)."%";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($departamento)."%";
			}

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa, 
					fecha_creacion, 
					area_has as area_definitiva_ha, 
					perimetro, 
					sistema_origen,
					municipios,
					centroide
					--coordenadas_bog
				from v_prospectos $where					
			";		
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Prospectos. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function ejecutarEstudiosTecnicosProspectos($idEmpresa, $placa) {
			$queryStr =  "select get_area_def_sgm($1, $2) as result";
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa, $placa));
			
			if (!$result) {
			  return "Error en ejecutarEstudiosTecnicosProspectos.";
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return $lista[0]["result"];				
		}
		
		function selectEstudiosTecnicosProspectos($idEmpresa, $placa) {
			$queryStr =  "select get_area_def_sgm($1, $2) as result";
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa, $placa));
			$Error = pg_last_error($this->conn);
			if($Error) {
			  echo "Error al ejecutar el estudio t&eacute;cnico de superposiciones para prospectos mineros. Error: $Error";
			  return 0;
			}							
			
			$queryStr =  "
				select distinct
					vas.area_estudio, 					
					vas.fecha_creacion, 
					vas.sistema_origen, 
					get_num(abg.area_ini/10000) as area_inicial_has,
					get_num(abg.area_fin/10000) as area_final_has,					
					vas.expediente_superpone, 
					vas.fecha_radica_inscribe, 
					vas.minerales_area_superpone, 
					vas.tipo_superposicion, 
					case when vas.recortar=1 then 'SI' else 'NO' end as recortar, 
					vas.modalidad_area_superpone, 
					vas.area_superposicion, 
					vas.porcentaje_superpone || '%' as porcentaje_superpone, 					
					get_num((abs(abg.area_ini-abg.area_fin)/abg.area_ini)*100) || '%' as porcentaje_recortado,
					personasByExpediente(vas.expediente_superpone,vas.tipo_superposicion) as titulares
					--getMpiosDeptosByProspectSGM(vas.area_estudio) as municipios_prospecto
					--ST_AsText(abg.the_geom) as coordenadas_resultantes
				from v_prospectos_sgm_superposiciones vas 
					inner join prospectos_superposiciones_bog_sgm abg on (vas.area_estudio=abg.placa)
				where placa=$1
				order by 2, 5
			";	
					
			$result = pg_query_params($this->conn, $queryStr, array($placa));

			if (pg_last_error($this->conn)) {
			  echo "Error al realizar la consulta de superposiciones para prospectos mineros sigmin. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function generaUrlViewMap($placa, $centroide, $areaPoly, $cobertura="solicitudes_cg", $tituloCobertura="Solicitudes") {
			$centroidesLonLat = explode(" ", substr($centroide,6,-1)); 		
			$centroideLon = $centroidesLonLat[0];
			$centroideLat = $centroidesLonLat[1];			
			
			$enlace = "codigoExpediente=$placa&centroideLon=$centroideLon&centroideLat=$centroideLat&cobertura=$cobertura&tituloCobertura=$tituloCobertura&areaPoly=$areaPoly";			
			return $enlace;		
		}
		
		function generarViewMap($placa, $clasificacion="SOLICITUD") { 
			$queryStr = "";
			
			if($clasificacion=='SOLICITUD') {
				$queryStr = "select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
										ST_AsText(sg.the_geom) as coordenadas,  
										get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(sar.sistema_origen)))/10000) as area_has
							from 		solicitudes_cg sg inner join solicitudes s on (s.id=sg.id_solicitud)
										inner join solicitudes_cg_bog sb on (s.id=sb.id_solicitud)
										left join sol_arcifinios_tmp sar on (s.id = sar.id_solicitud)	 
							where 	s.placa=$1";
			} else if ($clasificacion=='TITULO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(sar.sistema_origen)))/10000) as area_has
							from 	titulos_cg sg inner join titulos s on (s.id=sg.id_titulo)
								inner join titulos_cg_bog sb on (s.id=sb.id_titulo)
								left join tit_arcifinios_tmp sar on (s.id = sar.id_titulo)	 
							where	s.placa=$1	";							
			} else if ($clasificacion=='PROSPECTO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(sb.sistema_origen)))/10000) as area_has
							from 	prospectos_sgm sg inner join prospectos_bog_sgm sb on (sg.placa=sb.placa)
							where 	sg.placa=$1";
			} else if ($clasificacion=='ESTUDIO_TECNICO_PROSPECTO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(pb.sistema_origen)))/10000) as area_has
							from 	prospectos_superposiciones_sgm sg 
								inner join prospectos_bog_sgm pb on (sg.placa=pb.placa)
								inner join prospectos_superposiciones_bog_sgm sb on (sg.placa=sb.placa)	
							where 	sg.placa=$1";
			} 
		
			$result = pg_query_params($this->conn, $queryStr, array($placa));

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de areas en $clasificacion: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista[0])) return null;	

			return  $lista[0];
		}		
		
		function generarReporte($placa, $clasificacion="SOLICITUD") { 				
			$queryStr = "";
			
			if($clasificacion=='SOLICITUD') {
				$queryStr = "
					select distinct vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen)))/10000) as Area_Def_has
					from 	v_solicitudes vs 
						inner join solicitudes s on (vs.placa=s.placa)
						left join solicitudes_cg_bog sg on (s.id=sg.id_solicitud)
					where s.placa=$1
				";
			} else if ($clasificacion=='TITULO') {
				$queryStr =	"
					select  distinct s.id as id_titulo,
						vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen)))/10000) as Area_Def_has
					from 	v_titulos vs 
						inner join titulos s on (vs.placa=s.placa)
						left join titulos_cg_bog sg on (s.id=sg.id_titulo)
					where s.placa=$1
				";
			} else if ($clasificacion=='PROSPECTO') {
				$queryStr =	"
					select 	vp.placa,
						vp.fecha_creacion,
						vp.area_has,
						vp.municipios,
						vp.sistema_origen,
						ST_AsText(ST_Transform(pb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas
					from v_prospectos_sgm vp
						inner join prospectos_bog_sgm pb on (vp.placa=pb.placa)				
					where vp.placa=$1
				";
			} else if ($clasificacion=='ESTUDIO_SOLICITUD') {
				$queryStr =	"
					select  s.id as id_solicitud,
						vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						ST_AsText(ST_Transform(ss.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas_estudio,
						get_num(ss.area_ini/10000) as Area_Def_has,
						get_num(ss.area_fin/10000) as Area_Def_Estudio	
					from 	v_solicitudes vs 
						inner join solicitudes s on (vs.placa=s.placa)
						left join solicitudes_cg_bog sg on (s.id=sg.id_solicitud)
						left join areas_superposiciones_bog ss on (s.id=ss.id_solicitud)
					where vs.placa=$1
				";
			} else if ($clasificacion=='ESTUDIO_TECNICO_PROSPECTO') {
				$queryStr =	"
					select 	
						vp.placa,
						pb.fecha_creacion,
						get_num(round(cast(pb.area/10000 as numeric),4)) as area_has,
						getMpiosDeptosByProspectSGM(vp.placa) as municipios,
						pb.sistema_origen,
						ST_AsText(ST_Transform(pb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas,
						ST_AsText(ST_Transform(psb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas_estudio,
						get_num(psb.area_ini/10000) as area_def_has,
						get_num(psb.area_fin/10000) as area_def_estudio	
					from prospectos_sgm vp
						inner join prospectos_bog_sgm pb on (vp.placa=pb.placa)
						inner join prospectos_superposiciones_bog_sgm psb on (vp.placa=psb.placa)				
					where vp.placa=$1				
				";
			}						
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la generaci�n de reportes en $clasificacion: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista[0])) return null;	

			return  $lista[0];
		}		

		function get_GaussToWGS84($coordenadas, $origen) {
			$queryStr =  "select get_GaussToWGS84($1, $2) as result";			
			
			$result = pg_query_params($this->conn, $queryStr, array($coordenadas, $origen));
			if (!$result) {
			  echo "Error in get_GaussToWGS84 Transformation.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return $lista[0]["result"]; 			
		}

		function generarViewMultiMap($whereQuery) { 
			$queryStr = "";
/*			
			if($clasificacion=='SOLICITUD') {
				$queryStr = "select 	s.placa,  
										ST_AsText(sg.the_geom) as coordenadas,  
										'$clasificacion' as clasificacion
							from 		solicitudes_cg sg inner join solicitudes s on (s.id=sg.id_solicitud)
							where 	$whereQuery";
			} else if ($clasificacion=='TITULO') {
				$queryStr =	"select 	s.placa,  
										ST_AsText(sg.the_geom) as coordenadas,  
										'$clasificacion' as clasificacion
							from 		titulos_cg sg inner join titulos s on (s.id=sg.id_titulo)
							where 	$whereQuery";
			} 
*/
			$queryStr = "	
						select s.placa,  
									ST_AsText(sg.the_geom) as coordenadas,  
									'SOLICITUD' as clasificacion
							from 	solicitudes_cg sg inner join solicitudes s on (s.id=sg.id_solicitud)
										left join titulos t on s.placa=t.placa
							where 	t.placa is null and ($whereQuery)
						UNION
							select 	s.placa,  
										ST_AsText(sg.the_geom) as coordenadas,  
										'TITULO' as clasificacion
							from 		titulos_cg sg inner join titulos s on (s.id=sg.id_titulo)
							where 	($whereQuery)
						";
		
			$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la visualizacion multiple de areas en $clasificacion: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(empty($lista)) return null;	

			return  $lista;
		}

		function getIdentify($coordsPunto) {
			$queryStr =  "
				select 
					'SOLICITUD' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
//					s.fecha_radicacion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area_hec,
					getLineDataByIdSolicitud(s.id,'SOL_MUNICIPIOS') as municipios,
					getLineDataByIdSolicitud(s.id,'SOL_PERSONAS') as personas,
					getLineDataByIdSolicitud(s.id,'SOL_MINERALES') as minerales
				from   (select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join solicitudes_cg m on ST_Intersects(Idtf.identify, m.the_geom)
					inner join solicitudes s on (m.id_solicitud=s.id)
				union
				select 
					'TITULO' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
//					s.fecha_inscripcion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area,
					getLineDataByIdTitulo(s.id,'TIT_MUNICIPIOS') as municipios,
					getLineDataByIdTitulo(s.id,'TIT_PERSONAS') as personas,
					getLineDataByIdTitulo(s.id,'TIT_MINERALES') as minerales
				from   (select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join titulos_cg m on ST_Intersects(Idtf.identify, m.the_geom)
					inner join titulos s on (m.id_titulo=s.id)					
			";			
			
			$params = array($coordsPunto);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			return  $lista;
		}

		function getIdentifyRestricciones($coordsPunto) {
			$queryStr =  "
				select 
					zeb.nombre as zona_restriccion,
					tz.nombre as tipo_restriccion,
					-- zeb.observacion,
					to_char(zeb.fecha_ini_vigencia,'DD/MM/YYYY') as inicio_vigencia,
					(zeb.area)/10000 as area_hec,
					zeb.perimetro as perimetro_mts 
				from 	(select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join zonas_excluibles ze on ST_Intersects(Idtf.identify, ze.the_geom)
					inner join zonas_excluibles_bog zeb on ze.id_zona_excluible_bog = zeb.id
					inner join tipos_zonas_restriccion tz on zeb.id_tipo_zona_restriccion=tz.id	
						union
				select 
					zeb.nombre as zona_restriccion,
					tz.nombre as tipo_restriccion,
					-- zeb.observacion,
					to_char(zeb.fecha_ini_vigencia,'DD/MM/YYYY') as inicio_vigencia,
					(zeb.area)/10000 as area_hec,
					zeb.perimetro as perimetro_mts 
				from 	(select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join zonas_restriccion ze on ST_Intersects(Idtf.identify, ze.the_geom)
					inner join zonas_restriccion_bog zeb on ze.id_zona_restriccion_bog = zeb.id
					inner join tipos_zonas_restriccion tz on zeb.id_tipo_zona_restriccion=tz.id					
			";			
			
			$params = array($coordsPunto);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			return  $lista;
		}

		function getIdentifyMbl($coordsPunto) { // obtenci�n de coordenadas para dispositivos m�biles
			$queryStr =  "
				select 
					'SOLICITUD' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
					s.fecha_radicacion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area_hec,
					getLineDataByIdSolicitud(s.id,'SOL_MUNICIPIOS') as municipios,
					getLineDataByIdSolicitud(s.id,'SOL_PERSONAS') as personas,
					getLineDataByIdSolicitud(s.id,'SOL_MINERALES') as minerales,
					st_asText(m.the_geom) as coordenadas
				from   (select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join solicitudes_cg m on ST_Intersects(Idtf.identify, m.the_geom)
					inner join solicitudes s on (m.id_solicitud=s.id)
				union
				select 
					'TITULO' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
					s.fecha_inscripcion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area,
					getLineDataByIdTitulo(s.id,'TIT_MUNICIPIOS') as municipios,
					getLineDataByIdTitulo(s.id,'TIT_PERSONAS') as personas,
					getLineDataByIdTitulo(s.id,'TIT_MINERALES') as minerales,
					st_asText(m.the_geom) as coordenadas
				from   (select ST_GeometryFromText($1, get_sistema_origen('WGS84')) as identify) as Idtf
					inner join titulos_cg m on ST_Intersects(Idtf.identify, m.the_geom)
					inner join titulos s on (m.id_titulo=s.id)					
			";			
			
			$params = array($coordsPunto);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			return  $lista;
		}
		
		function getInfoByPlaca($placa) { // obtenci�n de coordenadas para dispositivos m�biles
			$queryStr =  "
				select 
					'SOLICITUD' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
					s.fecha_radicacion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area_hec,
					getLineDataByIdSolicitud(s.id,'SOL_MUNICIPIOS') as municipios,
					getLineDataByIdSolicitud(s.id,'SOL_PERSONAS') as personas,
					getLineDataByIdSolicitud(s.id,'SOL_MINERALES') as minerales,
					st_asText(cg.the_geom) as coordenadas
				from   	solicitudes s
					left join titulos t on s.placa=t.placa
					left join solicitudes_cg cg on cg.id_solicitud=s.id
					where s.placa=$1 and t.placa is null
				union
				select 
					'TITULO' as tipo_expediente,
					s.placa,
					s.modalidad,
					s.estado_juridico,
					s.fecha_inscripcion as fecha_radica_inscribe,
					get_num(round(cast(s.area_definitiva/10000 as numeric),4)) as area,
					getLineDataByIdTitulo(s.id,'TIT_MUNICIPIOS') as municipios,
					getLineDataByIdTitulo(s.id,'TIT_PERSONAS') as personas,
					getLineDataByIdTitulo(s.id,'TIT_MINERALES') as minerales,
					st_asText(cg.the_geom) as coordenadas
				from   
					titulos s 
					left join titulos_cg cg on (cg.id_titulo=s.id)
					where s.placa=$1					
			";			
			
			$params = array($placa);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			return  $lista;
		}

		function getCoordsByOrigen($coordsPunto, $origen) { // obtenci�n de coordenadas para dispositivos m�biles
			$queryStr =  "
				select 
					case when 'WGS84'<>$2 then 
						st_astext(st_transform(st_transform(st_geomfromtext($1,get_sistema_origen($2)), 4218), 4326)) 
					else $1 
					end as coordenada	
			";			
			
			$params = array($coordsPunto, $origen);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			pg_free_result($result);
			return $lista[0]; 
		}
		
	}	
?>

