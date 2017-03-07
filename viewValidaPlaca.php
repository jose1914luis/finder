<?php
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/IndexacionesQueries.php");
	require_once("Utilidades/ajustarAcentos.php");
	
/*	
	if(!empty($_POST["textoQuery"])) {
		$indexacion = new IndexacionesQueries(); 
		$registrosIdx = $indexacion->selectByValorCampo($_POST["textoQuery"]);
		
		$i = 0;
		foreach($registrosIdx as $cadaResult) 
			echo "<option value='".($i++)."'>".$cadaResult["valor_campo"];
	} 	
*/

//defino una clase que voy a utilizar para generar los elementos sugeridos en autocompletar
	class ElementoAutocompletar {
	   var $value;
	   var $label;
	   
	   function __construct($label, $value){
		  $this->label = $label;
		  $this->value = $value;
	   }
	}

	if(!empty($_GET["term"])) {
		$indexacion = new IndexacionesQueries(); 
		$registrosIdx = $indexacion->selectByValorPlaca(strtoupper($_GET["term"]));
		
		$i = 0;
		$arrayElementos = array();
		
		foreach($registrosIdx as $fila)   
			//creo el array de los elementos sugeridos
			 array_push($arrayElementos, new ElementoAutocompletar(AjustarAcentos($fila["valor_campo"]), AjustarAcentos($fila["valor_campo"])));

		print_r(json_encode($arrayElementos));
 
		
	} 
?>
