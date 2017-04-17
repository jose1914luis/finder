<?php
/*
	Clase encargada de la administración y gestión de los perfiles generados por empresa en el SIGMIN
*/

	class IndexacionesQueries {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase de Indezación.\n";
				return 0;
			}
		}
		
		function selectByValorCampo($valor) {
			//$valor = str_replace("|","",trim($valor));
			$cancelaQuery = "";
			if(empty($valor)) $cancelaQuery = "1=2 and";
			
			$queryStr =  "select valor_campo from indexamiento_consulta where $cancelaQuery valor_campo similar to $1  order by prioridad limit 10";	
			
			$valor = str_replace(" ","%",$valor);
			$result = pg_query_params($this->conn, $queryStr, array("((".$valor."%)|(%[^A-Za-z0-9;](".$valor.")%))"));
			if (!$result) {
			  echo "Error al consultar indexacion del query.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectSolicitudesByListaCampos($valor) {
			$listaQuery = "1=1";
			$valor = str_replace(array(utf8_encode("Ñ"), utf8_encode("ñ")), "%", $valor);
			$camposView = "
				placa, modalidad, estado_juridico, grupo_trabajo, fecha_radicacion, fecha_terminacion, area_solicitada_ha, area_definitiva_ha, direccion_correspondencia, telefono_contacto, municipios, solicitantes, minerales 						
			";		
			
			if(sizeof($valor)== 0) return 0;
		
			if($this->existeValorByEmpresa($valor)) {
				$queryStr 	=  "select $camposView from v_solicitudes_idx where solicitantes like $1";	
				$campos[0] 	= strtoupper("%$valor%");
			} else {
				$camposValidar = explode(" ", $valor); 
				if(sizeof($camposValidar)>0) {
					$i=1;
					
					foreach($camposValidar as $cadaCampo)
						if (!empty($cadaCampo)) {
							$listaQuery .= ' and criterios_consulta like $'.($i++);
							$campos[$i] =  strtoupper("%|$cadaCampo%");
						}
							
					$queryStr =  "select $camposView from v_solicitudes_idx where $listaQuery";	
				}
			}

			$result = pg_query_params($this->conn, $queryStr, $campos);
			if (!$result) {
			  echo "Error al consultar indexacion de solicitudes del query.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			return  $lista;			
		}

		function selectTitulosByListaCampos($valor) {	
			$listaQuery = "1=1";
			$valor = str_replace(array(utf8_encode("Ñ"), utf8_encode("ñ")), "%", $valor);
			$camposView = "
				placa, codigo_rmn, codigo_anterior, modalidad, estado_juridico, grupo_trabajo, fecha_inscripcion, fecha_terminacion, area_otorgada_ha, area_definitiva_ha, direccion_correspondencia, telefono_contacto, telefono_contacto, municipios, titulares, minerales
			";		
			
			if(sizeof($valor)== 0) return 0;
		
			if($this->existeValorByEmpresa($valor)) {
				$queryStr 	=  "select $camposView from v_titulos_idx where titulares like $1";	
				$campos[0] 	= strtoupper("%$valor%");
			} else {
				$camposValidar = explode(" ", $valor); 
				if(sizeof($camposValidar)>0) {
					$i=1;
					
					foreach($camposValidar as $cadaCampo)
						if (!empty($cadaCampo)) {
							$listaQuery .= ' and criterios_consulta like $'.($i++);
							$campos[$i] =  strtoupper("%|$cadaCampo%");
						}
							
					$queryStr =  "select $camposView from v_titulos_idx where $listaQuery";	
				}
			}

			$result = pg_query_params($this->conn, $queryStr, $campos);
			if (!$result) {
			  echo "Error al consultar indexacion de titulos del query.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			return  $lista;	
		}
		
		function existeValorByEmpresa($valor) {	
			if(sizeof($valor)>0) {
				$queryStr =  "
					select count(qry.id_expediente) as result from (
						select s.id as id_expediente
						from solicitudes s inner join sol_personas_tmp sp on (s.id=sp.id_solicitud)
						where nombre_persona like $1
						union
						select  t.id as id_expediente
						from titulos t inner join tit_personas_tmp sp on (t.id=sp.id_titulo)
						where nombre_persona like $1 
					) as qry				
				";	
				
				$result = pg_query_params($this->conn, $queryStr, array($valor));
				if (!$result) {
				  echo "Error al consultar existencia de persona en SIGMIN.\n";
				  return 0;
				}						
				$lista = pg_fetch_all($result);			
				pg_free_result($result);
				return  $lista["0"]["result"];
			} else 
				return 0;		
		}

		function selectDocumentQueryByListaCampos($valor) {
			$listaQuery = "1=1";
			$valor = str_replace(array(utf8_encode("Ñ"),utf8_encode("ñ"),utf8_encode("Á"),utf8_encode("á"),utf8_encode("É"),utf8_encode("é"),utf8_encode("Í"),utf8_encode("í"),utf8_encode("Ó"),utf8_encode("ó"),utf8_encode("Ú"),utf8_encode("ú")), "%", $valor);
			
			
			$camposView = "
				idfrm, formulario, placa, fecha_creacion , indice as campo_consultado, dato as informacion 						
			";		
			
			$camposValidar = explode(" ", $valor); 
			if(sizeof($camposValidar)>0) {
				$i=1;
				foreach($camposValidar as $cadaCampo)
					if (!empty($cadaCampo)) {
						$listaQuery .= ' and upper(criterios_consulta) like $'.($i);
						$campos[$i] =  strtoupper("%|$cadaCampo%");
						$i++;
					}
						
				$queryStr =  "select $camposView from keeper.v_document_query_idx where $listaQuery order by placa, formulario, fecha_creacion";	
			}

			$result = pg_query_params($this->conn, $queryStr, $campos);
			if (!$result) {
			  echo "Error al consultar indexacion de document management.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);
			
			pg_free_result($result);
			return  $lista;			
		}		
	}	
?>

