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
            $_SESSION['rango_superior'] = 500000;
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
    if (@$_GET["pagina"] == "account") {
        $_SESSION["pagina"] = "account";
        include("indexAccount.php");
    } else {
        if (empty($_GET["fnd"]) && empty($_GET["crd"]))
            include("indexFinder.php");
        else // Para resolver los popups de prospecto
            include("indexAccount.php");
    }
} else if (@$_POST["captcha2"]) {
    if ($_REQUEST['captcha2'] == "captcha2") {
        require_once("Modelos/Usuarios_SGM.php");
        require_once("Utilidades/LibCurl.php");

        $createUsr = new Usuarios_SGM();
        $msgCreate = $createUsr->insertUsrTmpAll($_POST);
        if ($msgCreate == 'OK') {
            $serial = $createUsr->getSerialbyEmail($_POST["txtEmail"]);

            $url = "http://www.sigmin.com.co/EmailServices/sendEmail.php";
            $params = array(
                'email' => $_POST["txtEmail"],
                'identificacion' => $_POST["txtDocumento"],
                'nombre' => $_POST["txtNombre"],
                'codigoVerificacion' => $serial,
                'urlActivaUsuario' => "email={$_POST["txtEmail"]}&identificacion={$_POST["txtDocumento"]}&codigo_verificacion=" . $serial
            );

            $connCurl = new LibCurl;
            $resultado = $connCurl->curl_download($url, $params);
            $emailRs = json_decode($resultado, true);

            $msgAcceso = "<script>alert('Usuario creado satisfactoriamente. Verifique en el email {$_POST["txtEmail"]} los pasos para acceder a SIGMIN')</script>";
        } else {
            $msgAcceso = "<script>alert('Error al crear el usuario: $msgCreate')</script>";
            ;
        }
    } else {
        # set the error code so that we can display it
        $msgAcceso = "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>";
        ;
    }
    include("indexLogin.php");
} else if (@$_POST["captcha"]) {

    if ($_REQUEST['captcha'] == "captcha") {

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

            $url = "http://www.sigmin.com.co/EmailServices/sendEmailChangePwd.php";
            $params = array(
                'login_tmp' => $usuario["login"],
                'passwd_tmp' => $usuario["claveNew"],
                'email_pwd' => $_POST["txtEmail"]
            );

            $connCurl = new LibCurl;
            $resultado = $connCurl->curl_download($url, $params);
            $emailRs = json_decode($resultado, true);
				

            $msgAcceso = "<script>alert('Nueva clave generada satisfactoriamente. Verifique en el email {$_POST["txtEmail"]} los pasos para acceder a SIGMIN')</script>";
        } else {
            $msgAcceso = "<script>alert('El correo electronico {$_POST["txtEmail"]} no se encuentra registrado')</script>";
        }
    }

    include("indexLogin.php");
} else
    include("indexLogin.php");

if ($msgAcceso)
    echo $msgAcceso;

