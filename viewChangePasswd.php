<?php
	@session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios_SGM.php");
	
	if(empty($_SESSION['usuario_sgm'])) echo "alert('Error en cambio de Clave. La p\u00E1gina no es accesible ...');";
	else {
		$usr["claveOld"]= $_POST["claveOld"];
		$usr["claveNew"]= $_POST["claveNew"];
		$usr["login"]	= $_SESSION['usuario_sgm'];

		$validate 		= new Usuarios_SGM(); 
		$msgValidacion 	= $validate->cambiarContraseniaUsuario($usr);
		
		if($msgValidacion == 'O.K') 
			$msgValidacion = 'La contrase\u00F1a ha sido actualizada correctamente'; 		
	}
	

?>		
	alert('<?php echo $msgValidacion ?>');
	
	
