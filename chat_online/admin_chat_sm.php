<?php
		session_start();
		
		require_once("../Acceso/Config.php");
		require_once("../Modelos/Bitacora_Chats.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

		$usuarioChat 	= "soportesgm";
		$pwdChat 		= md5("chatsigmin2015");
		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Chat – Customer Module</title>
<link type="text/css" rel="stylesheet" href="style.css" />
<script type="text/javascript" src="javascript/jquery.min.js"></script>
</head>
<body onload="if(confirm('Se va a recargar la página y se perderán las respuestas no enviadas, recargar?')) setTimeout('document.location.reload()',300000);">

	<?php
		function loginForm()	{
			echo'
					<div id="loginform">
					<form action="admin_chat_sm.php" method="post">
					<p>Ingresa Usuario y Contrase&ntilde;a:</p>
					<label for="user">Usuario:</label> <input type="text" name="usuario_sm" id="usuario_sm" value="soportesgm"/><br/>
					<label for="pwd">Contrase&ntilde;a:</label>  <input type="password" name="password_sm" id="password_sm"  value="" /><br/> 
					<input type="submit" name="enter" id="ingresar" value="Ingresar" />
					</form>
					</div>
				';
		}
		
		if(isset($_POST['enter']))	{
			if($_POST['usuario_sm'] != "" && md5($_POST['password_sm'])== $pwdChat && $_POST['usuario_sm']== $usuarioChat)	{
							
				$_SESSION['usuario_sigmin']	= stripslashes(htmlspecialchars($_POST['usuario_sm']));
				$_SESSION['pwd_sigmin']		= stripslashes(htmlspecialchars($_POST['password_sm']));
									
			}
			else	{
				echo "alert('Usuario o clave inválidos, intente nuevamente')";
				echo '<script>document.location.href="http://www.sigmin.co/finderaccount/chat_online/";</script>';
			}
		}

	
	?>


	<?php
		if(!isset($_SESSION['usuario_sigmin']))	{
			loginForm();
		}
		else {
			
			$chats = new Bitacora_Chats;
			//$_SESSION["lista_archivos"] = $chats->selectArchivosByFecha('2015-01-20');//(date("Y-m-d"));
			$_SESSION["lista_archivos"] = $chats->selectArchivosByFecha(date("Y-m-d"));	
						
			if(empty($_SESSION["lista_archivos"])) {
?>				
				<hr size="0">
					<center><h1>:: SIGMIN CHAT ONLINE ::</h1></center>
				<hr size="0">
					<center>
						<h2>
							No hay chats Pendientes de atenci&oacute;n
						</h2>
					</center>
				<hr size="0">
					
<?php				
				exit;
			}
		
			
?>
	<hr size="0">
		<center>
			<h1>:: SIGMIN CHAT ONLINE ::</h1>
		</center>
	<hr size="0">

	<hr size="0">
		
	<table border="0" align="center">
<?php
			$nroCuadros = 3;
			for($i = 0; $i < sizeof($_SESSION["lista_archivos"]); $i++)	 {
				
				if ($i%$nroCuadros==0) {					
					?>
					<tr><td>
					<?php
				} else {
					?>
					</td><td>
					<?php
				}
				
				$archivo_cht = $_SESSION["lista_archivos"][$i]["nombre_archivo"];
	?>
				<p>
				<div id="wrapper">
				<div id="menu">
				<p class="welcome"><b>(<?=($i+1); ?>)</b> Respuesta al chat con: <b><?php echo strtoupper($_SESSION["lista_archivos"][$i]["nombre_usuario"]) ?></b></p>
				
				<!-- <p class="logout"><a id="exit" href="#">Exit Chat</a></p> -->
				<div style="clear:both"></div>
				</div>
				<div id="chatbox<?php echo $i ?>" style="text-align:left; margin:0 auto; margin-bottom:25px; padding:10px; background:#fff; height:270px; width:430px; border:1px solid #ACD8F0; overflow:auto; ">
				<?php
					if(file_exists($archivo_cht) && filesize($archivo_cht) > 0){
						$handle = fopen($archivo_cht, "r");
						$contents = fread($handle, filesize($archivo_cht));
						fclose($handle);
						echo $contents;
					}
				?>
				</div>
				<form name="message<?php echo $i ?>" action="">
				<input name="usermsg<?php echo $i ?>" type="text" id="usermsg<?php echo $i ?>" size="63" />
				<input name="submitmsg<?php echo $i ?>" type="submit"  id="submitmsg<?php echo $i ?>" value="Send" />
				</form>
				</div>
				<script type="text/javascript">
				// jQuery Document
				$(document).ready(function(){	
					$("#submitmsg<?php echo $i ?>").click(function(){
						var clientmsg = $("#usermsg<?php echo $i ?>").val();
						$.post("post1.php", {text: clientmsg, id: <?php echo $i ?> });
						$("#usermsg<?php echo $i ?>").attr("value", "");
						return false;
					});				
				});		

				//Carga el archivo que contiene el log de chat
				function loadLog<?php echo $i ?>(){
					$.ajax({
						url: "<?php echo $archivo_cht ?>",
						cache: false,
						success: function(html){
							$("#chatbox<?php echo $i ?>").html(html + "<span id='final<?php echo $i ?>'></span>"); //Inserta el log de chat en el div #chatbox
							document.getElementById('final<?php echo $i ?>').scrollIntoView(true);
							$("#chatbox").scrollTop = '9999';						
						},
					});
				}
				
				setInterval (loadLog<?php echo $i ?>, 2500);    //Recarga el archivo cada 2500 ms o x ms si deseas cambiar el segundo parámetro			
			</script>
					<?php
						if (($i+1)%$nroCuadros==0) {					
					?>
						</td><tr>
					<?php
						} 
			}
?>
	</table>	
	
<?php			
		}
	?>

</body>
</html>