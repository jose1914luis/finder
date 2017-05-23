<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ImagenesDocumentos.php"); // Librería para el tratamiento de las imagenes asociadas al documento	
	
	//$json = json_encode(print_r($_FILES, true));		
		
	if (isset($_FILES["fileToUpload"]["name"]) && $_FILES["fileToUpload"]["name"]!="") {

		//datos del arhivo 
		$nombre_archivo = $_FILES['fileToUpload']['name'];	//$HTTP_POST_FILES['imagenFile']['name']; 
		$carpetaArchivo = $GLOBALS["tmpImgs"];
		$tipo_archivo 	= $_FILES['fileToUpload']['type']; 
		$tamano_archivo = $_FILES['fileToUpload']['size']; 
		
		$extension 		= split("\.", $_FILES['fileToUpload']['name']);
		$nombreTemp		= split("\/", $_FILES['fileToUpload']['tmp_name']);
		$nombreTemp[2]	.= ".".$extension[1];
		
		// Validación de extensiones
		// Archivos Permitidos: gif, jpg ó jpeg, png, tiff, pdf
		// sin sensibilidad a letras mayúsculas ni minúsculas
		$patron = "%\.(gif|jp?g|png|ti?f|pdf)$%i";
		// Ejemplo de visualización del resultado
		$validaExtension = preg_match($patron, $nombre_archivo) == 1 ? 1 : 0;

		if($validaExtension)  {				
			//datos del arhivo  
			if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $carpetaArchivo.$nombreTemp[2])){ 
				$tmpImgs= new ImagenesDocumentos();
				$opr = $tmpImgs->insertTemp(session_id(),$nombreTemp[2]);
				if($opr == "OK")
					$json= "{error: '', msg: 'Archivo almacenado correctamente', urlImagen: '".$nombreTemp[2]."', extension: '{$extension[1]}' }";
				else
					$json= "{error: '', msg: '$opr'}";
			}else{ 
				$json= "{error: '', msg: 'Error al guardar la imagen'}";	 
			} 
			
		}	else   {  
			$json= "{error: '', msg: 'La extension no es valida'}";	
		}
		
		echo $json;
	} else 
		echo "{error: '', msg: 'Error - no se encuentra el nombre del archivo'}";
?>