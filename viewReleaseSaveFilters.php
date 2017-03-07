<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/FiltrosLiberaciones.php");

	function validaCampo($valor) {
		if(empty($valor)) 	return "";
		else				return $valor;
	}
	
	if(!empty($_SESSION['id_usuario'])) {
		$filtro = new FiltrosLiberaciones();
		
		$filtroArray["minerales"] 	= validaCampo($_POST["minerales"]);
		$filtroArray["personas"] 	= validaCampo($_POST["personas"]);
		$filtroArray["municipios"] 	= validaCampo($_POST["municipios"]);
		$filtroArray["modalidad"] 	= validaCampo($_POST["modalidad"]);
		
		$queryArr 	= json_encode($filtroArray);
		$resp		= $filtro->insertAll( $_SESSION['id_usuario'], $queryArr);
		if($resp=="OK") echo "alert('Filtro almacenado correctamente')";
		else 			echo "alert('$resp')";
		
	} else 
		echo "alert('Error inesperado al guardar el filtro');";
	
?>
