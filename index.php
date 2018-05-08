<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("Acceso/Config.php");
require_once("Modelos/EstadisticasUsuarios.php");
require_once("Modelos/geoiploc.php");
require_once("viewValidaQuery.php");
require_once("viewServicesSIGMINFullResultados.php");
require_once("Modelos/ProspectosBogSGM.php");
//require_once("Modelos/SeguimientosUsuarios.php");	
require_once("Modelos/Usuarios_SGM.php");
require_once("Modelos/CreditosUsuarios.php");
require_once("Modelos/ExpedientesSGM.php");
require_once("Vistas/v_pie_pagina.php");

include './Correo/Correo.php';

//  Definici�n de las variables globales 		
$validate = new Usuarios_SGM();
$prp = new ProspectosBogSGM();
$cred = new CreditosUsuarios();

$msgAcceso = "";
$msgSistema = "";

// --------- validaci�n del tiempo de session ---------	
$inactivo = 60 * 60; // inactividad de una hora
if (isset($_SESSION["tiempo"])) {
    $vida_session = time() - $_SESSION["tiempo"];
    if ($vida_session > $inactivo) {
        include_once("Vistas/v_exit.php");
    }
}
$_SESSION["tiempo"] = time();
// --------- fin de la validacion del tiempo de session ---------
// --------- validaci�n del usuario ---------

if (@$_SESSION["usuario_sgm"] != "" && !$validate->validaPasswd($_SESSION["usuario_sgm"], $_SESSION["passwd_sgm"])) {
    include_once("Vistas/v_exit.php");
}
// ------------------------------------------

$CKEYfile = fopen("CKEY.txt", "r") or die("Unable to open file!");
$CKEY = fgets($CKEYfile);
fclose($CKEYfile);

if (isset($_POST["username"]) && isset($_POST["password"])) {
    if ($validate->validaPasswd($_POST["username"], $_POST["password"])) {
        $_SESSION['usuario_sgm'] = $_POST["username"];
        $_SESSION['passwd_sgm'] = $_POST["password"];
        $_SESSION['id_usuario'] = $validate->getIDbyLogin($_POST["username"]);

        $_SESSION['usr_cred'] = $cred->getInfoCreditosByIdUsuario($_SESSION['id_usuario']);

        $controlUsuario = new EstadisticasUsuarios();
        if ($controlUsuario->setLogeoUsuario($_SESSION['usuario_sgm']) == "O.K") {
            $Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);
            if (empty($Id_Empresa) || !$Id_Empresa)
                echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";

            if (trim(@$_POST["codigo_uso"]) != "") {
                $msgSistema = $cred->usarCreditosPromocion($_SESSION['id_usuario'], $_POST["codigo_uso"]);
                if ($msgSistema == 'OK')
                    $msgSistema = "Cr\u00E9ditos asignados satisfactoriamente";
            }

            // Variables globales para generaci�n de archivos Excel
            $_SESSION["myExcelSolicitudesFile"] = "";
            $_SESSION["myExcelTitulosFile"] = "";
            $_SESSION["idEmpresa"] = $Id_Empresa;
            $_SESSION["pagina"] = "account";
            $_SESSION['rango_inferior'] = 0;
			
			// validación de rango máximo permitido en captura de polígonos
			if($_SESSION['usuario_sgm']!="jecardenas" && $_SESSION['usuario_sgm']!="jmoreno" && $_SESSION['usuario_sgm']!='jvelasquez')
				$_SESSION['rango_superior'] = 500000;
			else
				$_SESSION['rango_superior'] = 99999999;
			
            // variables del controlador	
            $msgError = "";
            include("indexAccount.php");
        } else
            echo "<script>document.location.href='{$GLOBALS ["url_error"]}'</script>";
    } else {
        $msgAcceso = "<script>alert('Usuario o Clave invalidos');</script>";
        include("indexLogin.php");
    }
} else if (@$_SESSION["pagina"] == "account") {
    if (@$_GET["pagina"] == "map") {
        $_SESSION["pagina"] = "map";
        include("indexFinder.php");
    } else {
        include("indexAccount.php");
    }
} else if (@$_SESSION["pagina"] == "map") {
    if (isset($_GET["mnu"])) {
        $_SESSION["pagina"] = "account";
        include("indexAccount.php");
    } else {
        if (empty($_GET["fnd"]) && empty($_GET["crd"]))
            include("indexFinder.php");
        else // Para resolver los popups de prospecto
            include("indexAccount.php");
    }
} else if (@$_POST["captcha2"]) {
    if (isset($_POST['g-recaptcha-response'])) {

        $captcha = $_POST['g-recaptcha-response'];

        if (!$captcha) {
            $msgAcceso = "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>";
        } else {
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".preg_replace('/\s+/', '', $CKEY)."&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            if ($response['success'] == false) {
                $msgAcceso = "<script>alert('Usuario Spam.')</script>";
            } else {

                //if ($_REQUEST['captcha2'] == "captcha2") {
                require_once("Modelos/Usuarios_SGM.php");
                require_once("Utilidades/LibCurl.php");

                $createUsr = new Usuarios_SGM();
                $msgCreate = $createUsr->insertUsrTmpAll($_POST);
                if ($msgCreate == 'OK') {
                    $serial = $createUsr->getSerialbyEmail($_POST["txtEmail"]);

                    $correo = new Correo();
                    $correo->usuarioNuevo($_POST["txtNombre"], "email={$_POST["txtEmail"]}&identificacion={$_POST["txtDocumento"]}&codigo_verificacion=" . $serial, $_POST["txtEmail"]);                                      
                    
                    $msgAcceso = "<script>alert('Usuario creado satisfactoriamente. Verifique en el email {$_POST["txtEmail"]} los pasos para acceder a SIGMIN')</script>";
                } else {
                    $msgAcceso = "<script>alert('Error al crear el usuario: $msgCreate')</script>";
                }
            }
        }
    } else {
        # set the error code so that we can display it
        $msgAcceso = "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>";
    }
    include("indexLogin.php");
} else if (@$_POST["captcha"]) {


    if (isset($_POST['g-recaptcha-response'])) {
        //if ($_REQUEST['captcha'] == "captcha") {
        $captcha = $_POST['g-recaptcha-response'];

        if (!$captcha) {
            $msgAcceso = "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>";
        } else {

            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=". preg_replace('/\s+/', '', $CKEY). "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);

            if ($response['success'] == false) {
                $msgAcceso = "<script>alert('Usuario Correo Spam.')</script>";
            } else {
                // Si el c�digo captcha es correcto
                require_once("Modelos/Usuarios_SGM.php");
                require_once("Utilidades/LibCurl.php");
                require_once("Utilidades/GenerarContrasenia.php");

                $changePwdUsr = new Usuarios_SGM();
                $usuario["email"] = $_POST["txtEmail"];
                $usuario["login"] = $changePwdUsr->getLoginByEmail($_POST["txtEmail"]);

                if (!empty($usuario["login"])) {
                    $usuario["claveNew"] = generaPass(8); // Pasword de 8 caracteres. 
                    $changePwdUsr->asignarPwdAleatorio($usuario);                    
                    $correo = new Correo();
                    $correo->recuperarContra($usuario["claveNew"], $usuario["login"], $_POST["txtEmail"]);

                    $msgAcceso = "<script>alert('Nueva clave generada satisfactoriamente. Verifique en el email {$_POST["txtEmail"]} los pasos para acceder a SIGMIN')</script>";
                } else {
                    $msgAcceso = "<script>alert('El correo electronico {$_POST["txtEmail"]} no se encuentra registrado')</script>";
                }
            }
        }
    } else {
        # set the error code so that we can display it
        $msgAcceso = "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>";
    }

    include("indexLogin.php");
} else
    include("indexLogin.php");

if ($msgAcceso)
    echo $msgAcceso;

