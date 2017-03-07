<?php
	session_start();
	
error_reporting(E_ALL);
ini_set('display_errors', 1);

	require_once("../Acceso/Config.php");
	require_once("../Modelos/Bitacora_Chats.php");
	
	$mensajesBienvenida = array (
		"Es un placer para SIGMIN atenderle, en que podemos colaborar?",
		"Muchas gracias por dar la oportunidad a SIGMIN de atenderle, en que podemos colaborar?",
		"Bienvenido al mundo minero en Colombia, nuestro equipo de soporte SIGMIN queda a su disposici&oacute;n.",
		"SIGMIN es su mejor aliado en la b&uacute;squeda de oportunidades de inversi&oacute;n en Colombia. En que podemos colaborar?",
		"A trav&eacute;s de SIGMIN puede obtener las mejores oportunidades mineras en Colombia, Bienvenido a nuestro mundo."
	);

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>:: Chat Online - SIGMIN ::</title>
<link type="text/css" rel="stylesheet" href="style.css" />
<script type="text/javascript" src="javascript/jquery.min.js"></script>
</head>
<body>
<div align="center">
	<?php
		if(@$_SESSION['session_id'] != session_id())	{
			$_SESSION['session_id'] = session_id();
			$nombreFile				= md5(time());
			$_SESSION['name'] 		= stripslashes(htmlspecialchars($_SESSION['usr_cred']['nombre']));
			$_SESSION['email'] 		= stripslashes(htmlspecialchars($_SESSION['usuario_sgm']));
			$nomArchivo				= explode(" ", str_replace(" ", "-", $_SESSION['name']));
			$_SESSION['archivo'] 	= "chats/".$nombreFile.".html";  // nombre del archivo de chat en la session   
			
			$newChat = new Bitacora_Chats;		
			$newChat->insertAll(array("name"=>$_SESSION['name'], "email"=>$_SESSION['email'], "archivo"=>$_SESSION['archivo']));
			
			// Envío de email para notificar del usuario que requiere soporte						
			$usuario_minero = str_replace(" ", "%20", $_SESSION['name']);						
			$email_minero 	= $_SESSION['email'];						
			$url = "http://www.sigmin.com.co/emailNotificaChat.php?usuario_minero=$usuario_minero&email_minero=$email_minero";						
			$cc = curl_init($url);  						
			curl_setopt($cc, CURLOPT_RETURNTRANSFER, true);
			$resp =  curl_exec($cc);
			//echo "<hr>".$resp."<hr>";						
			curl_error($cc);  						
			curl_close($cc); 										
		}

		
		if(isset($_GET['logout']))	{
			//Mensaje simple de salida
			$fp = fopen($_SESSION['archivo'], 'a');
			fwrite($fp, "<div class='msgln'><i>User ".$_SESSION['name'] ." ha dejado la sesi&oacute;n de chat.</i><br><hr></div>");
			fclose($fp);
			session_destroy();
			header("Location: index.php"); //Redirige al usuario
		}		
	?>

			<div id="wrapper">
			<div id="menu">
			<p class="welcome">Bienvenido(a), <b><?php echo $_SESSION['name']; ?></b></p>
			
			<p class="logout"><a id="exit" href="#">Salir del Chat</a></p>
			<div style="clear:both"></div>
			</div>
			<div id="chatbox">
			<?php
				if(file_exists($_SESSION['archivo']) && filesize($_SESSION['archivo']) > 0){
					$handle = fopen($_SESSION['archivo'], "r");
					$contents = fread($handle, filesize($_SESSION['archivo']));
					fclose($handle);
					echo $contents;
				} else {
					$nroMsg = mt_rand(0, 4);
					$mensaje = "<div class='adminbox'>(".date("d-m-Y g:i A").") <b>SIGMIN</b>: ".stripslashes($mensajesBienvenida[$nroMsg])."<br></div>";
					echo $mensaje;
					$fp 	= fopen($_SESSION['archivo'], 'a');		
					fwrite($fp, $mensaje);		
					fclose($fp);											
				}
			?>
			</div>
			<form name="message" action="">
				<input name="usermsg" type="text" id="usermsg" size="63" />
				<input name="submitmsg" type="submit"  id="submitmsg" value="Enviar" />
			</form>
			</div>
			<script type="text/javascript">
			// jQuery Document
			$(document).ready(function()  {
				//Si el usuario quiere dejar la sesión
				$("#exit").click(function(){
					var exit = confirm("Esta seguro de abandonar la sesión?");
					if(exit==true){
						window.close();
					}
				});
				
				$("#submitmsg").click(function(){
					var clientmsg = $("#usermsg").val();
					$.post("post.php", {text: clientmsg});
					$("#usermsg").attr("value", "");
					
					
					return false;
				});				
			});		

			//Carga el archivo que contiene el log de chat
			function loadLog(){
				$.ajax({
					url: "<?php echo $_SESSION['archivo'] ?>",
					cache: false,
					success: function(html){
						$("#chatbox").html(html + "<span id='final'></span>"); //Inserta el log de chat en el div #chatbox
						document.getElementById('final').scrollIntoView(true);
						$("#chatbox").scrollTop = '9999';	
					},
				});				
			}
			
			setInterval (loadLog, 2500);    //Recarga el archivo cada 2500 ms o x ms si deseas cambiar el segundo parámetro			
		</script>

</div>
</body>
</html>