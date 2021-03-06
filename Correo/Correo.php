<?php

$upOne = realpath(__DIR__ . '/');
include $upOne . '/sendgrid-php/sendgrid-php.php';

class Correo {

    private $apiKey = '';
    private $from;
    private $subject;
    private $to;
    private $content;

    public function __construct() {
        $AKEYfile = fopen("AKEY.txt", "r") or die("Unable to open file!");
        $AKEY = fgets($AKEYfile);
        $this->apiKey = preg_replace('/\s+/', '', $AKEY);
        fclose($AKEYfile);
    }

    public function enviar_email($emails, $asuntoMsg, $body) {


        $this->from = new SendGrid\Email(null, "sigmin@sigmin.com.co");
        $this->to = new SendGrid\Email(null, $emails);
        $this->subject = $asuntoMsg;

        // mensaje
        $mensaje = "
		<html>
		<head>
		  <title> $asuntoMsg </title>
		</head>
		<body>
			$body
		</body>
		</html>
		";
        $this->content = new SendGrid\Content("text/html", $mensaje);
        $mail = new SendGrid\Mail($this->from, $this->subject, $this->to, $this->content);
        $sg = new \SendGrid($this->apiKey);
        $response = $sg->client->mail()->send()->post($mail);

        return $response;
    }

    public function usuarioNuevo($nombre, $urlActivaUsuario, $email){
    
        

	$mensaje = '	
		<html>
		<head>
		<title>SIGMIN :: Creación de Cuenta </title>
		</head>
		<body>
		<table border="0" align="center" width="600">
		<tr>
		<td>
		<center><h1>Bienvenido(a) al Catastro Minero Online, SIGMIN -<h1></center>	
		<hr size="0">

		<p>Apreciado(a) '.strtoupper(utf8_decode($nombre)).',
		<p>Con una cuenta en SIGMIN usted tendr&aacute; acceso al Catastro Minero Online, donde encontrar&aacute; la informaci&oacute;n actualizada de solicitudes y t&iacute;tulos mineros de Colombia, con la posibilidad de visualizarlos f&aacute;cilmente sobre los mapas de Google.</p>
		<p>Para activar su <i><b>CUENTA GRATUITA</b></i> haga <b><a href="http://www.sigmin.co/registrese_en_sigmin.php?'.$urlActivaUsuario.'" style="text-decoration:none;" target="_top">CLICK AQU&Iacute;</a></b></p>
		<p> RECUERDE: Su usuario (username) de acceso al sistema es: <i><u>'.$email.'</u></i></p>
		<p>Para nosotros ser&aacute; un placer atenderle, pondremos todo nuestro empe&ntilde;o para garantizarle un buen servicio.</p>
		<p>Atentamente,</p>
		<p><b>Jos&eacute; Ernesto C&aacute;rdenas Santamar&iacute;a<br>
		Director General<br>
		SIGMIN S.A.S</b><br>		
		<div style="font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px; background-color: #555555;	color: #ffffff;	text-align: center;">
			<div>&nbsp;</div>
			<div>
				<div>Calle. 5a # 43b-25 Of 301, Edificio Meridian. Medellín-Colombia</div>
				<div>Tel&eacute;fono: (574) 322 70 04 - M&oacute;vil: 314 716 0680</div>
				<div><a href="mailto:contactenos@sigmin.com.co" style="text-decoration:none; color:#ffffff">contactenos@sigmin.com.co</a></div>
				<div>:: SIGMIN S.A.S. 2012 - TODOS LOS DERECHOS RESERVADOS &reg; ::</div>
			</div>
			<div style="padding-bottom:10px;">&nbsp;</div>
		</div>

		</td>
		</tr>
		</body>
		</html>';	
        
        return $this->enviar_email($email, "Creación de Cuenta :: SIGMIN", $mensaje);
    }
    
    public function recuperarContra($passwd_tmp, $login_tmp, $email_pwd) {

        $mensaje = '	
		<html>
		<head>
		<title>SIGMIN :: Cambio de Clave</title>
		</head>
		<body>
			<table border="0" align="center" width="600">
				<tr>
					<td>
						<center><h1>Usted ha solicitado cambio de clave en SIGMIN<h1></center>	
						<hr size="0">
						<p>A continuaci&oacute;n se encuentran las instrucciones para el cambio de clave de su cuenta </p>
						<ol>
							<li>Su usuario de acceso a cuenta sigmin es: ' . $login_tmp . '</li>
							<li>Su Clave temporal es : ' . $passwd_tmp . '</li>
							<li>Ingrese a <a href="http://www.sigmin.co/finder/">SIGMIN</a> y actualice su clave mediante la opci&oacute;n <i>ACCOUNT</i></li>
						</ol>
					</td>
				</tr>
				<tr>	
					<td>
						<p>Atentamente,</p>
						<p><b>Soporte T&eacute;cnico<br>
						SIGMIN S.A.S</b><br>		
						<div style="font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px; background-color: #555555;	color: #ffffff;	text-align: center;">
							<div>&nbsp;</div>
							<div>
								<div>Calle 7 Sur # 42-70 Of 1101, Edificio Forum. Medell&iacute;n-Colombia</div>
								<div>Tel&eacute;fono: (574) 322 70 04 - M&oacute;vil: 314 716 0680</div>
								<div><a href="mailto:contactenos@sigmin.com.co" style="text-decoration:none; color:#ffffff">contactenos@sigmin.com.co</a></div>
								<div>:: SIGMIN S.A.S. 2012 - TODOS LOS DERECHOS RESERVADOS &reg; ::</div>
							</div>
							<div style="padding-bottom:10px;">&nbsp;</div>
						</div>
					</td>
				</tr>
			</table>
		</body>
		</html>';

        //$this->enviar_email($emails, $asuntoMsg, $body)
        $this->enviar_email($email_pwd, "Cambio de Clave :: SIGMIN", $mensaje);
    }

}
