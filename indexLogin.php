	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
		<meta name="apple-mobile-web-app-capable" content="yes">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script src="Javascript/jquery.placeholder.min.js"></script>   
		<script src="Javascript/procesarUsrLogin.js"></script>   
		<link rel="stylesheet" href="Javascript/login.css" type="text/css" media="all" />
		<title>:: SIGMIN :: Mining Properties</title>
		<style>
			html { overflow-y: hidden; }
			#result { border: 1px solid green; width: 300px; margin: 0 0 35px 0; padding: 10px 20px; font-weight: bold; }
			#change-image { font-size: 0.8em; }		
		</style>
	</head>
	<body class="login_bg">
	<?php include_once("analyticstracking.php") ?>
		<div class="login_box">
			<div>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/></div>
			<center><div class="logo_login">Sigmin</div></center>
			<div>&nbsp;<br/>&nbsp;</div>
			<div>
				<form method="post">
					<input type="text" placeholder="Correo Electrónico" id="username" name="username" class="campo-input"  required/><br/>
					<input type="password"  name="password" id="password"  placeholder="Clave" class="campo-input"  required/><br/> 
					<a href="javascript:" class="forgot" onclick="mostrardiv('lyAdminUserPwd')"><b>Olvid&oacute; su Contrase&ntilde;a?</b></a><br/>								
					<div>&nbsp;<br/></div>
					<div class="redimir">Redime tu Bono<br/></div>
					<input type="text"  name="codigo_uso" id="codigo_uso"  placeholder="Ingresa tu Código" title="Solo si posee cupón de cortesía de SIGMIN" class="campo-promocion"/><br/> 
					<div>&nbsp;</div>					
					<input type="submit" class="login_boton" value="  INGRESAR  " />
					<div>
						<center><b><a href="javascript:" onclick="mostrardiv('lyAdminUser')" class="registernow">&nbsp;&nbsp;Reg&iacute;strese Gratis&nbsp;&nbsp;</a></b></center>
					</div>
				</form>
			</div>	
			<div>&nbsp;<br/>&nbsp;</div>

		</div>


		
				
		<form name="frmAdminUser" method="post" action="index.php">
			<div id="lyAdminUser" class="adminUser" style="display: none;">	
				<div class="titleAdminUser">    
				<center><strong>:: &nbsp;&nbsp;&nbsp;Creaci&oacute;n de Usuario&nbsp;&nbsp;&nbsp; ::</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php	if(empty($_GET["register"])) {	?>
					<a href="javascript:" onclick="cerrar('lyAdminUser')" title="Cerrar ventana" style="color: #ffffff; text-decoration: none">[X]</a>
				<?php	}		?>
				</center></div>
				<div>
					<table border="0" align="center" width="95%" class="tableUser">
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>					
						<tr>
							<td>N&uacute;mero de Documento:</td>
							<td><input type="text" name="txtDocumento"> *</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td><input type="text" name="txtNombre" size="30"> *</td>
						</tr>
						<tr>
							<td>Correo Electr&oacute;nico:</td>
							<td><input type="text" name="txtEmail" size="30"> *</td>
						</tr>
						<tr>
							<td>Contrase&ntilde;a:</td>
							<td><input type="password" name="txtPassword"> *</td>
						</tr>
						<tr>
							<td>Repetir Contrase&ntilde;a:</td>
							<td><input type="password" name="txtPassword2"> *</td>
						</tr>						
						<tr>
							<td colspan="2"><hr size="0"></td>
						</tr>						
						<tr>
							<td colspan="2">								
								<center>
									<div>
										<img src="captcha.php" id="captcha2" /><br/>
										<input type="text" name="captcha2" id="captcha-form2" autocomplete="off" placeholder="Type the Text"/> &nbsp;	
										<a href="javascript:" style="text-decoration:none" onclick="document.getElementById('captcha2').src='captcha.php?'+Math.random();	document.getElementById('captcha-form2').focus();"	id="change-image2"><img width="25" height="17" src="http://www.google.com/recaptcha/api/img/red/refresh.gif" title="Get a new challenge"></a>														
									</div>								
								</center>
							</td>
						</tr>	
						<tr>
							<td colspan="2"><hr size="0"></td>
						</tr>						
						<tr>
							<td colspan="2">
								<center>
									<input type="button" value="Crear Usuario" onclick="validarCreacionUsr(); cerrar('lyAdminUser');"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php	if(empty($_GET["register"])) {	?>
									<input type="button" value="Cancelar" onclick="cerrar('lyAdminUser')">
								<?php } ?>	
								</center>
							</td>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr size="0"></td>
						</tr>						
					</table>
				</div>
			</div >
		</form>

		<form name="frmForgetPwd" method="post">
			<div id="lyAdminUserPwd" class="adminUser" style="display: none;">	
				<div class="titleAdminUser">    
					<center><strong>:: &nbsp;&nbsp;&nbsp;Recuperar Contrase&ntilde;a&nbsp;&nbsp;&nbsp; ::</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:" onclick="cerrar('lyAdminUserPwd')" title="Cerrar ventana" style="color: #ffffff; text-decoration: none">[X]</a></center></div>
				<div>
					<table border="0" align="center" width="95%" class="tableUser">	
						<tr>
							<td colspan="2">
								<p>Ingrese el Correo Electr&oacute;nico con el cual usted tiene registrada la cuenta, all&iacute; le será suministrada una clave aleatoria para acceso</p>
								<p>Recuerde utilizar la opción <b><i>"Account"</i></b> para asignar una nueva clave de acceso.</p>
							</td>
						</tr>	
						<tr>
							<td>Correo Electr&oacute;nico:</td>
							<td><input type="text" name="txtEmail" size="30"> *</td>
						</tr>					
						<tr>
							<td colspan="2"><hr size="0"></td>
						</tr>						
						<tr>
							<td colspan="2">
								<center>
									<div>
										<img src="captcha.php" id="captcha" /><br/>
										<input type="text" name="captcha" id="captcha-form" autocomplete="off" placeholder="Type the Text"/> &nbsp;	
										<a href="javascript:" style="text-decoration:none" onclick="document.getElementById('captcha').src='captcha.php?'+Math.random();	document.getElementById('captcha-form').focus();"	id="change-image"><img width="25" height="17" src="http://www.google.com/recaptcha/api/img/red/refresh.gif" title="Get a new challenge"></a>														
									</div>																	
								</center>
							</td>
						</tr>	
						<tr>
							<td colspan="2"><hr size="0"></td>
						</tr>						
						<tr>
							<td colspan="2">
								<center>
									<input type="button" value="Recuperar Contraseña" onclick="validarRecuperarUsr()"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" value="Cancelar" onclick="cerrar('lyAdminUserPwd')">
								</center>
							</td>
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr size="0"></td>
					</tr>						
				</table>
			</div>
		</div >
	</form>

		
        <script>
			$('input[placeholder], textarea[placeholder]').placeholder();
		</script>
		<?php
			if(!empty($_GET["register"]) && strlen($_GET["register"])==15) 
				echo "<script>mostrardiv('lyAdminUser')</script>";
		?>
	</body>
	</html>
