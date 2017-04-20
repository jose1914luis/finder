<?php

class ValidacionesFormularios {

	function getValidacionTipoDato($labelCampo, $nombreCampo, $tipoDato) {
		$sentenciaIf = "document.frm01.$nombreCampo.value.search(patron)<0";
		
		if($tipoDato == "ENTERO") {
			$patron 		= "/^[0-9]+$/";
			$ErrorCampo 	= "'$labelCampo' debe ser num\u00E9rico";
		} else if ($tipoDato == "DECIMAL") {
			$patron 		= "/^[0-9]*\.?[0-9]{0,}$/; document.frm01.$nombreCampo.value.replace(',','.')";
			$ErrorCampo 	= "'$labelCampo' debe ser num\u00E9rico decimal";			
		} else if ($tipoDato == "TEXTO" || $tipoDato == "TEXTO LARGO") {
			$patron 		= "/[A-Za-z0-9]+/";
			$ErrorCampo 	= "'$labelCampo' se encuentra vacio o nulo";			
		} else if ($tipoDato == "MONEDA") {
			$patron 		= "/^[0-9]*\.?[0-9]{0,2}$/; document.frm01.$nombreCampo.value.replace(',','.')";
			$ErrorCampo 	= "'$labelCampo' debe ser num\u00E9rico de m\u00E1ximo dos decimales";			
		} else if ($tipoDato == "FECHA DOCUMENTO" || $tipoDato == "FECHA INICIA TERMINO" || $tipoDato == "FECHA VENCE TERMINO" || $tipoDato == "FECHA CUMPLIMIENTO TERMINO") {
			$patron 		= "/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}([ ]{1}([0-1]?[0-9]|2[0-3]):[0-5][0-9]){0,1}$/";
			$ErrorCampo 	= "'$labelCampo' no posee un formato v\u00E1lido";						
		} else if ($tipoDato == "LISTA DE SELECCION") {
			$patron 		= "''";
			$sentenciaIf 	= "document.frm01.$nombreCampo.options[document.frm01.$nombreCampo.selectedIndex].value==0";			
			$ErrorCampo 	= "'$labelCampo' no ha sido seleccionado";			
		} else if ($tipoDato == "LISTA SELECCION MULTIPLE") {
			$patron 		= "''";
			$sentenciaIf 	= "document.frm01[\"".$nombreCampo."[]\"].selectedIndex == 0";			
			$ErrorCampo 	= "'$labelCampo' no ha sido seleccionado";			
		} else if ($tipoDato == "EMAIL") {
			$patron 		= "/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/";
			$ErrorCampo 	= "'$labelCampo' no posee un formato v\u00E1lido";						
		} else if ($tipoDato == "LISTA CON RADIOBOTON") {
			$patron 		= "''";
			$sentenciaIf 	= "!validaRadio($nombreCampo)";	
			$ErrorCampo 	= "'$labelCampo' no ha seleccionado opci\u00F3n";						
		} else if ($tipoDato == "LISTA DE CHEQUEO") {
			return ""; // no hay validación
		}
		$dato = "
			patron = $patron; 
			if ($sentenciaIf) {
				alert(\"$ErrorCampo\");
				document.frm01.$nombreCampo.focus();
				return 0;
			}	
		";		
		
		return $dato;	
	}	
	
	function validaIndexamiento($indicesValidar) {
		$codExpediente 	= $this->getValidacionTipoDato("C\u00F3digo de Expediente", "codigoExpediente", "TEXTO");
		$nroRadicado 	= $this->getValidacionTipoDato("N\u00FAmero de Radicado", "nroRadicado", "TEXTO");
		$fechaRadicado 	= $this->getValidacionTipoDato("Fecha de Radicado", "fechaRadicado", "FECHA DOCUMENTO");
		$nroFolios 		= $this->getValidacionTipoDato("N\u00FAmero de Folios", "cantidadImagenes", "ENTERO");
		$docReferencia	= $this->getValidacionTipoDato("Referencia del Documento", "docReferencia", "TEXTO LARGO");
		$solReq			= $this->getValidacionTipoDato("Entidad que genera documento", "solRequerimiento", "LISTA DE SELECCION");
		$fileToUpload	= $this->getValidacionTipoDato("Seleccionar Archivo", "fileToUpload", "TEXTO");
		
		$codigoIndices 	= "";
		
		if(!empty($indicesValidar))
			foreach($indicesValidar as $cadaIndice) 
				if($cadaIndice["requerido"]==1)
					$codigoIndices .= $this->getValidacionTipoDato(ucwords(strtolower($cadaIndice["nombre_indice"])),"indice_".$cadaIndice["id_indice"],$cadaIndice["tipo_dato"]);

		$validador =  "
			<script>
				function validaRadio(nomCampo) {
					var checked = false, radios = document.getElementsById(nomCampo);
					for (var i = 0, radio; radio = radios[i]; i++) {
						if (radio.checked) {
							checked = true;
							break;
						}
					}
					if (!checked)	return false;
						return true;
				}
				
				function validarIndexamiento() {	
					$codExpediente			
					$nroRadicado
					$fechaRadicado
					$nroFolios	
					$docReferencia
					$solReq
					$codigoIndices					
					$fileToUpload
					/* una vez efectuadas todas las validaciones, se procede a enviar el formulario: */
					document.frm01.submit();
				}
			</script>	
		";
		
		$order   		= array("\r\n", "\n", "\r", "\t");
		$replace 		= '';	
		return str_replace($order, $replace, $validador); 
		
	}	
}


	
	
	



?>
	