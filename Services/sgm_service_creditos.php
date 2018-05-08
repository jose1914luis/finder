<?php
header('Access-Control-Allow-Origin: *');
require '../Acceso/Config.php';
require_once("../Modelos/CreditosUsuarios.php");
require_once("../Modelos/Usuarios_SGM.php");

if (isset($_GET)) {

    //seleciono el id del usuario
    $login = filter_input(INPUT_GET, 'login');
    $operacion = filter_input(INPUT_GET, 'operacion');
    $usuario = new Usuarios_SGM();

    $idUsuario = $usuario->getIDbyLogin($login);
    if ($idUsuario != 0) {

        $creditos = new CreditosUsuarios();
        if ($operacion == "consulta") {

            echo json_encode($creditos->getInfoCreditosByIdUsuario($idUsuario));
        } elseif ($operacion == "consumir") {

            $idProducto = 13;
            $verMapa = $creditos->compraCreditosViewMap($idUsuario, $idProducto);
            if ($verMapa == "OK") {

                echo $verMapa;
            }
        }
    }
} else {

    echo 0;
}