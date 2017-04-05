<?php
session_start();
header('Access-Control-Allow-Origin: *');

require '../Acceso/Config.php';
require_once("../Modelos/Usuarios_SGM.php");
require_once("../Utilidades/LibCurl.php");

$createUsr = new Usuarios_SGM();
$msgCreate = $createUsr->insertUsrTmpAll($_GET);
if ($msgCreate == 'OK') {
    $serial = $createUsr->getSerialbyEmail($_GET["txtEmail"]);

    $url = "http://www.sigmin.com.co/EmailServices/sendEmail.php";
    $params = array(
        'email' => $_GET["txtEmail"],   
        'identificacion' => $_GET["txtDocumento"],
        'nombre' => $_GET["txtNombre"],
        'codigoVerificacion' => $serial,
        'urlActivaUsuario' => "email={$_GET["txtEmail"]}&identificacion={$_GET["txtDocumento"]}&codigo_verificacion=" . $serial
    );

    $connCurl = new LibCurl;
    $resultado = $connCurl->curl_download($url, $params);
    $emailRs = json_decode($resultado, true);

    echo 1;
} else {
    echo 0;
}