<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');	
	

	function generarCodigo($longitud) {
		 $key = '';
		 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
		 $max = strlen($pattern)-1;
		 for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
			return $key;
	}	
 
	//Ejemplo de uso
	echo "<hr>";
	for($i=1;$i<=500;$i++)  
		echo "insert into servicios.creditos_promocionales (numero_creditos, clave_uso) values (50, '".generarCodigo(6)."'); <br/>"; // genera un código de 6 caracteres de longitud.

	
?>	