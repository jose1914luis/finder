<?php

	
	//session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	require_once("Acceso/Config.php");

	require_once("Modelos/Usuarios_SGM.php");

	$mostrar = false;
	$mostrar2 = false;

	if(!empty($_SESSION["id_usuario"]) && $_SESSION["usuario_rol"] == "ADMIN") {
		
		if (isset($_POST["usuario"]) && isset($_POST["creditos"])){
			$usr = new Usuarios_SGM();
			$lista = $usr->insertCreditos($_POST["usuario"], $_POST["creditos"]);
			if($lista == false){
				$mostrar = false;
				$mostrar2 = true;
			}else{
				$mostrar = true;
			}
		}

		?>	
		<div class="container">

			<div style="width: 500px">

				<?php
				if($mostrar){
					?>	
					<div>
						<div class="alert alert-success" role="alert">Creditos actulizados!!</div>
					</div>
					<?php
				}

				if($mostrar2){
					?>	
					<div>
						<div class="alert alert-danger" role="alert">Error actulizando creditos!!</div>
					</div>
					<?php
				}
				?>
				

				<div class="panel panel-default">
				  <div class="panel-heading"><h3>Agregar creditos</h3></div>
				  <div class="panel-body">
				    
					<form method="POST">
					  <div class="form-group">
					    <label for="exampleInputEmail1">Usuario</label>
					    <input type="text" class="form-control" name="usuario" placeholder="Usuario">
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword1">Nro de Creditos</label>
					    <input type="number" class="form-control" name="creditos" id="exampleInputPassword1">
					  </div>		  
					  <button type="submit" class="btn btn-primary">Agregar</button>
					</form>
				  </div>
				</div>
			</div>						
		</div>
		

		<?php
	} else {
?>
	<script>alert("Error inesperado en el sistema");</script>
<?php		
	}
	
?>


