<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
header('Access-Control-Allow-Origin: *');

require '../Acceso/Config.php';
require_once("../Modelos/Usuarios_SGM.php");
require_once("../Utilidades/LibCurl.php");
include '../Correo/Correo.php';

$createUsr = new Usuarios_SGM();
$msgCreate = $createUsr->insertUsrTmpAll($_GET);
if ($msgCreate == 'OK') {
    $serial = $createUsr->getSerialbyEmail($_GET["txtEmail"]);
    
    $correo = new Correo();
    $res = $correo->usuarioNuevo($_GET["txtNombre"], "email={$_GET["txtEmail"]}&identificacion={$_GET["txtDocumento"]}&codigo_verificacion=" . $serial, $_GET["txtEmail"]);                                                         

    echo $res;
} else {
    echo 0;
}