<? 
$destinatario = "jmoreno084@gmail.com"; 
$asunto = "Este mensaje es de prueba"; 
$cuerpo = ' 
<html> 
<head> 
   <title>Prueba de correo</title> 
</head> 
<body> 
<h1>Hola amigos!</h1> 
<p> 
<b>Bienvenidos a mi correo electr�nico de prueba</b>. Estoy encantado de tener tantos lectores. Este cuerpo del mensaje es del art�culo de env�o de mails por PHP. Habr�a que cambiarlo para poner tu propio cuerpo. Por cierto, cambia tambi�n las cabeceras del mensaje.
</p> 
</body> 
</html> 
'; 

//para el env�o en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//direcci�n del remitente 
$headers .= "From: SIGMIN <alert@sigmin.co>\r\n"; 

//direcciones que recibi�n copia 
$headers .= "Cc: jamoreno@r2d2.com.co\r\n"; 
//direcciones que recibir�n copia oculta 
$headers .= "Bcc: alerts@sigmin.co\r\n"; 

$res = mail($destinatario,$asunto,$cuerpo,$headers);

echo "Respuesta es: $res <hr>"; 
?>