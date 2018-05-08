<?php

// Variable para controlar el include en plantilla.php
$IncludeVista = "1";

$_SESSION['usr_cred'] = $cred->getInfoCreditosByIdUsuario($_SESSION['id_usuario']);

// Arreglo de producto y tipo_expediente para definir clasificación 
$clasificacion["Reporte de expediente"]["TITULO"] = "TITULO";
$clasificacion["Reporte de expediente"]["SOLICITUD"] = "SOLICITUD";
$clasificacion["Reporte de prospecto"]["PROSPECTO"] = "PROSPECTO";
$clasificacion["Reporte de area libre"]["PROSPECTO"] = "ESTUDIO_TECNICO_PROSPECTO";
$clasificacion["Reporte de Superposiciones"]["PROSPECTO"] = "REPORTE SUPERPOSICIONES";
$clasificacion["Alerta de Liberacion de Area"]["PROSPECTO"] = "PROSPECTO";
$clasificacion["Analisis Perimetral"]["PERIMETRAL"] = "PERIMETRAL";

if (@$_GET["mnu"] == "propiedades_mineras")
    $paginaCargue = "v_propiedades_mineras.php";
else if (@$_GET["mnu"] == "expedientes") {
    require_once("Modelos/ExpedientesUsuarios.php");
    $expediente_por_usr = new ExpedientesUsuarios();

    //$_POST 				= val_input($_POST);
    if (@$_POST["txtBusqueda"] != "" && @$_POST["txtNombrePry"] != "") {
        $msg = $expediente_por_usr->insert($_SESSION['id_usuario'], $_POST["txtBusqueda"], @$_POST["txtNombrePry"]);
        $msgSistema = ($msg == "OK") ? "Placa asociada correctamente" : $msg;
    } else if (@$_GET["placa"] != "") {
        $msg = $expediente_por_usr->inactivarExpediente($_SESSION['id_usuario'], $_GET["placa"]);
        $msgSistema = ($msg == "OK") ? "Expediente {$_GET["placa"]} inactivo satisfactoriamente" : $msg;
    }

    $listaExpedientes = $expediente_por_usr->selectByIdUser($_SESSION['id_usuario']);
    $paginaCargue = "v_expedientes.php";
} else if (@$_GET["mnu"] == "expedientes_placa") {
    $IncludeVista = 0;
    include("reporteAreasAccount.php");	
	// $paginaCargue = "reporteAreasAccount.php";
} else if (@$_GET["mnu"] == "prospectos") {
    require_once("Modelos/ProspectosBogSGM.php");
    if (trim(@$_POST["placa"]) != "" && @$_POST["act"] == "delete") {
        $prp = new ProspectosBogSGM();
        $msg = $prp->deleteProspecto($_POST["placa"], $_SESSION['id_usuario']);
        $msgSistema = ($msg == "OK") ? "Prospecto {$_POST["placa"]} eliminado satisfactoriamente" : "";
    }

    $paginaCargue = "v_prospectos.php";
} else if (@$_GET["mnu"] == "prospectos_rsl")
    $paginaCargue = "v_prospectos.rsl.php";
else if (@$_GET["mnu"] == "descargas") {
    require_once("Modelos/DescargarShapes.php");
    $idProducto = 4;

    if (@$_POST["txtPlacas"] != "" & empty($_POST["aceptar"])) {
        $shpFiles = new DescargarShapes;
        $placas = $shpFiles->borrarCaracteres($_POST["txtPlacas"]);
        $credito = $cred->costoCreditoByProducto($idProducto);

        $listaValidada = $shpFiles->validacionExpedientesDescargar($placas);
    }

    $paginaCargue = "v_descargas.php";
} else if (@$_GET["mnu"] == "liberaciones" || @$_GET["crd"] == "liberaciones") {
    //if(@$_POST["compraRelease"] > 0)
    //	$msgSistema	= $cred->compraCreditosRelease($_SESSION['id_usuario'], $_POST["compraRelease"]);
    //if($cred->validaLiberacionAreaUsuario($_SESSION['id_usuario']))		
    $paginaCargue = "v_liberaciones.php";
    //else 
    //$paginaCargue = "v_liberaciones_adquirir.php";	
} else if (@$_GET["mnu"] == "creditos") {
    $_SESSION['usr_cred'] = $cred->getInfoCreditosByIdUsuario($_SESSION['id_usuario']);
    // $listaCreditosHistorial = $cred->getCreditosHistoricosByIdUsuario($_SESSION['id_usuario']);
    $paginaCargue = "v_creditos.php";
} else if (@$_GET["mnu"] == "creditos_compra") {
    $_SESSION['my_session'] = session_id();

    if (@$_POST["txtCompraCreditos"] < $GLOBALS ["SIGCoin_minimo"]) {
        $msgSistema = "La compra m\u00EDnima permitida es de " . $GLOBALS ["SIGCoin_minimo"] . " COP";
        echo "<script>
					alert('$msgSistema');
					window.close();	
				</script>";
    } else if (!$validate->validarCaracterizacion($_SESSION['id_usuario'])) {
        $msgSistema = "Antes de comprar cr\u00E9ditos debes diligenciar el siguiente formulario";
        echo "<script>
					alert('$msgSistema');
					document.location.href='?mnu=datos_basicos&credits=1';	
				</script>";
    } else
        include_once("Vistas/v_compra_creditos.php");
}
else if (@$_GET["mnu"] == "noticias")
    $paginaCargue = "v_noticias.php";
else if (@$_GET["mnu"] == "datos_basicos") {
    require_once("Modelos/Departamentos.php");
    require_once("Modelos/TiposDocumentos.php");

    $deptos = new Departamentos();
    $identificacion = new TiposDocumentos();
    $usr = new Usuarios_SGM();

    $_POST = val_input($_POST);
    if (@$_POST["txtDocumento"] != "") {
        // validacion de intento malicioso de modificacion de documento
        $docUsuario = $usr->getDocumentoByID($_SESSION['id_usuario']);
        if ($docUsuario == $_POST["txtDocumento"]) {
            $msg = $usr->saveCaracterizacion($_POST);
            if (isset($_POST['claveOld']) && isset($_POST['claveNew'])) {
                $msg = $usr->cambiarContraseniaUsuario($_POST);
            }


            if (@$_GET["credits"] != "1") {

                $msgSistema = ($msg == "OK") ? "Informaci\u00F3n almacenada correctamente" : $msg;
                echo "<script>alert('" . $msgSistema . "')</script>";
            } else {
                $IncludeVista = 0;
                echo "<script>alert('Informaci\u00F3n almacenada correctamente. Contin\u00FAa con tu compra de cr\u00E9ditos'); document.location.href='?mnu=creditos';</script>";
            }
        } else
            $msgSistema = "Modificaci\u00F3n no autorizada de informaci\u00F3n";
    }

    $listaForm = $usr->getCaracterizacionByIdUsr($_SESSION['id_usuario']);
    $readonly = ($listaForm["nombres"] == "" && $listaForm["razon_social"] == "") ? "" : "readonly";

    $paginaCargue = "v_datos_basicos.php";
}
else if (@$_GET["mnu"] == "contactenos") {
    require_once("Modelos/Contactenos.php");

    $_POST = val_input($_POST);
    if (trim(@$_POST["txtObservacion"]) != "") {
        $contactenos = new Contactenos();
        $msg = $contactenos->insertAll($_SESSION['id_usuario'], $_POST["txtObservacion"]);
        $msgSistema = ($msg == "OK") ? "Sugerencia enviada correctamente, muchas gracias por sus comentarios" : $msg;
    }

    $paginaCargue = "v_contactenos.php";
} else if (@$_GET["mnu"] == "change_passwd")
    $paginaCargue = "v_passwd.php";
else if (@$_GET["mnu"] == "logout")
    $paginaCargue = "v_exit.php";
else if (@$_GET["crd"] == "prospecto" || @$_GET["crd"] == "expediente" || @$_GET["crd"] == "analisis_area" || @$_GET["crd"] == "alerta_prospecto") {
    $IncludeVista = 0;
    include("reporteAreasAccount.php");
} else if (@$_GET["crd"] == "dwn_shapes") { // para descarga de shape de area libre
    $IncludeVista = 0;
    include("Vistas/v_downloadProspecto.php");
} else if (@$_GET["crd"] == "dwn_prospecto") { // para descarga de shape de prospecto
    $IncludeVista = 0;
    include("Vistas/v_downloadProspectoShape.php");
} else if (@$_GET["crd"] == "dwn_expedientes_placa") { // para descarga de shape de area libre
    $IncludeVista = 0;
    include("Vistas/v_downloadExpedientePlaca.php");
} else if (@$_GET["crd"] == "dwn_expedientes") { // para descarga de shape de expediente
    $IncludeVista = 0;
    include("Vistas/v_downloadExpediente.php");
} else if (@$_GET["crd"] == "superposiciones_area") { // para descarga de lista de superposiciones
    $IncludeVista = 0;
    include("Vistas/v_superposicionesArea.php");
} else if (@$_GET["crd"] == "notificaciones") { // para descarga de shape de expediente
    $IncludeVista = 0;
    include("Vistas/v_notificacionesExpediente.php");
} else if (@$_GET["fnd"] == "prospectos") {

    if (@$_POST["coordenadasPry"] != "") {
        $area = $cred->getArea($_POST["coordenadasPry"]);

        if ($area <= $_SESSION['rango_superior']) {
            $IncludeVista = 0;
            if (@$_POST["tipoOperaPry"] == "crear prospecto") {
                $placa = $prp->crearProspecto();
                $resultado = $prp->insertAll($_POST, $_SESSION["idEmpresa"], $_SESSION["id_usuario"], $placa);
                if ($resultado == "OK")
                    $accionPry = "document.location.href='?fnd=prospectos&crd=prospecto&placa=$placa&clasificacion=PROSPECTO'";
                else
                    $accionPry = "alert('Error en la generaci\u00F3n del Prospecto $placa'); window.close();";
                echo "<script>$accionPry</script>";
            }
            else if (@$_POST["tipoOperaPry"] == "superponer") {
                $placa = $prp->crearProspecto();
                $resultado = $prp->insertAll($_POST, $_SESSION["idEmpresa"], $_SESSION["id_usuario"], $placa);
                if ($resultado == "OK")
                    $accionPry = "document.location.href='?fnd=prospectos&crd=prospecto&placa=$placa&clasificacion=ESTUDIO_TECNICO_PROSPECTO&credits=1'";
                else
                    $accionPry = "aler('Error en la generaci\u00F3n del Reporte de Area Libre de $placa'); window.close();";
                echo "<script>$accionPry</script>";
            } else
                echo "<script>window.close();</script>";
        } else
            echo "<script>alert('El \u00C1rea supera el m\u00E1ximo l\u00EDmite permitido de {$_SESSION['rango_superior']} Hect.'); window.close();</script>";
    } else
        echo "<script>alert('No fue dibujado un pol\u00EDgono en el mapa'); window.close();</script>";
}
else if (@$_GET["fnd"] == "generar_alerta") {

    require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/Alertas.php");
    $monitoreo = new Alertas();
    $IncludeVista = 0;

    if (@$_POST["coordenadasPry"] != "") {
        $area = $cred->getArea($_POST["coordenadasPry"]);

        if ($area <= $_SESSION['rango_superior']) {
            $idProductoCred = 11;
            $placa = $prp->crearProspecto();
            $msgSistema = $prp->insertAll($_POST, $_SESSION["idEmpresa"], $_SESSION["id_usuario"], $placa);

            if ($msgSistema == "OK") {
                $msgSistema = $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $placa);
                $idCredYaConsumido = $cred->getIdCreditoConsumidoByPlacaYProd($placa, $idProductoCred);
                if ($msgSistema == "OK" && @$idCredYaConsumido > 0) {
                    $msgSistema = $prp->crearAlertaAreaLibre($placa, $_SESSION["usuario_sgm"]);
                    if ($msgSistema == "OK") {
                        $msgSistema = $monitoreo->montoreoArchivoExpedientes($placa);
                        if ($msgSistema == "OK") {
                            /*
                              Envio de email de notificacion de alerta al usuario
                             */
                            echo "<script>document.location.href='?crd=alerta_prospecto&creditos_prod=$idCredYaConsumido&placa=$placa&clasificacion=PROSPECTO';</script>";
                        }
                        echo "<script>alert('Error en monitoreo: $msgSistema'); window.close();</script>";
                    } else
                        echo "<script>alert('Error al generar alerta: $msgSistema'); window.close();</script>";
                } else
                    echo "<script>alert('$msgSistema'); window.close();</script>";
            } else
                echo "<script>alert('Error al generar prospecto: $msgSistema'); window.close();</script>";
        } else
            echo "<script>alert('El \u00C1rea supera el m\u00E1ximo l\u00EDmite permitido de {$_SESSION['rango_superior']} Hect.'); window.close();</script>";
    } else
        echo "<script>alert('No fue dibujado un pol\u00EDgono en el mapa'); window.close();</script>";
}
else if (@$_GET["fnd"] == "simular_coordenadas") {
    $IncludeVista = 0;
    include("viewLoadCoordinates.php");
} else if (@$_GET["crd"] == "analisis_perimetral" || @$_GET["fnd"] == "analisis_perimetral") {
    $IncludeVista = 0;
    include("Vistas/v_analisisPerimetral.php");
} else if (@$_GET["fnd"] == "identify_map") {
    $IncludeVista = 0;
    include("Vistas/v_identifyMap.php");
} else if (@$_GET["fnd"] == "valida_area") {
    $IncludeVista = 0;
    echo $cred->getArea($_POST["coordenadasPry"]);
} else {
    $_SESSION['usr_cred'] = $cred->getInfoCreditosByIdUsuario($_SESSION['id_usuario']);
    $listaCreditosHistorial = $cred->getCreditosHistoricosByIdUsuario($_SESSION['id_usuario']);
    $paginaCargue = "v_creditos.php";
}

if ($IncludeVista)
    include("Vistas/plantilla.php");
?>