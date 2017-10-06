<?php
if (empty($_GET["pagina"])) {
    $listaCreditosHistorial = $cred->getCreditosHistoricosByIdUsuario($_SESSION['id_usuario']);
    //$prospects 								= $prp->selectProspectosByUsuario($_SESSION["id_usuario"]);
    // Para paginación del resultado
    $max_por_pagina = $GLOBALS ["max_por_pagina"];
    $_SESSION["lista_credito"]["paginas"] = (!empty($listaCreditosHistorial)) ? array_chunk($listaCreditosHistorial, $max_por_pagina) : null;
    $_SESSION["lista_credito"]["caption"] = "Reporte de Cr&eacute;ditos Consumidos";
}

$total_paginas = sizeof(@$_SESSION["lista_credito"]["paginas"]);

$pagina_actual = (@$_GET["pagina"] > 1) ? $pagina_actual = $_GET["pagina"] : 1;
$dataSET = @$_SESSION["lista_credito"]["paginas"][$pagina_actual - 1];

// generación de listado de paginación, paginas por pantalla:
$pags_pantalla = $GLOBALS ["pags_pantalla"];

function generaPaginacion($url, $pags_pantalla, $pagina_actual, $total_paginas) {
    $pagInicial = floor(($pagina_actual - 1) / $pags_pantalla) * $pags_pantalla + 1;
    $pagFinal = ($pagInicial + $pags_pantalla - 1 > $total_paginas) ? $total_paginas : $pagInicial + $pags_pantalla - 1;

    $pagAnt = ($pagInicial - 1 < 1) ? 1 : $pagInicial - 1;
    $pagSte = ($pagFinal + 1 > $total_paginas) ? $total_paginas : $pagFinal + 1;

    $paginacion = '<ul class="pagination"><li><a href="' . $url . '&pagina=' . $pagAnt . '">«</a></li>';
    for ($i = $pagInicial; $i <= $pagFinal; $i++) {
        $activar = '';
        if ($i == $pagina_actual)
            $activar = ' class="active" ';
        $paginacion .= '<li><a ' . $activar . ' href="' . $url . '&pagina=' . $i . '">' . $i . '</a></li>';
    }
    $paginacion .= '<li><a href="' . $url . '&pagina=' . $pagSte . '">»</a></li></ul>';
    return $paginacion;
}

// fin de variables de paginación del resultado
if (!empty($dataSET))
    $tfoot = generaPaginacion("?mnu=creditos", $pags_pantalla, $pagina_actual, $total_paginas, $max_por_pagina);
else
    $tfoot = "<b><i>No reporta consumo de Cr&eacute;ditos</i></b>";
?>

<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading"><h4>Mis Créditos</h4></div>
    <div class="panel-body">

        <center><h4><b>Total de Cr&eacute;ditos disponibles:</b> <?= $_SESSION['usr_cred']['credito'] ?> Cr&eacute;dito(s)</h4></center>

        <hr>
        <form name="frmCreditos" method="post" action="/?mnu=creditos_compra" target="_blank" class="form-inline">
            <div class="form-group">
                <label for="exampleInputName2">Compra de nuevos cr&eacute;ditos (1 Cr&eacute;dito <=> 1000 COP):</label>
                <input type="text"  class="form-control" name="txtCompraCreditos" placeholder="Mínimo <?= $GLOBALS ["SIGCoin_minimo"] ?> COP" onchange="this.value = Math.floor(this.value / 1000) * 1000"/>

            </div>           
            <button type="button" onclick="document.frmCreditos.submit()" class="btn btn-success">
                Enviar Pago <span class="glyphicon glyphicon-credit-card"></span>
            </button>
        </form>

    </div>
    <div class="titleSite" style="text-align:center">Reporte de Cr&eacute;ditos</div>
    <table class="table table-striped" align="center" width="100%">	
        <tfoot>
            <tr>
                <td colspan="6" align="center">
                    <ul class="pagination">
                        <?= $tfoot ?>
                    </ul>					
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr class="results">
                <th class="results">Descarga</th>
                <th class="results">Producto</th>
                <th class="results">Placa</th>
                <th class="results">Cr&eacute;ditos Consumidos</th>
                <th class="results">Fecha Consumo</th>
                <th class="results">Fecha Vence</th>
            </tr>

            <?php
            if (!empty($dataSET))
                foreach ($dataSET as $cadaCredito) {
                    $cadaClasificacion = (@$clasificacion[$cadaCredito["producto"]][$cadaCredito["tipo_expediente"]] != "") ? "&clasificacion=" . $clasificacion[$cadaCredito["producto"]][$cadaCredito["tipo_expediente"]] : "";

                    $target = 'target="_blank"';
                    if ($cadaCredito["url_descarga"] == "?crd=liberaciones")
                        $target = "";
                    ?>
                    <tr class="results">
                        <td align="center" class="results"><center>
                    <a href="<?= $cadaCredito["url_descarga"] ?>&creditos_prod=<?= $cadaCredito["credito_prod"] ?>&placa=<?= $cadaCredito["placa"] ?><?= $cadaClasificacion ?>" title="Descarga del producto" <?= $target ?>><img src='Imgs/reportIcon.png' border='0' width='30' height='30'></center></a>
                    </td>					
                    <td class="results"><?= $cadaCredito["producto"] ?></td>
                    <td class="results"><?= $cadaCredito["placa"] ?></td>
                    <td class="results"><?= $cadaCredito["creditos_consumidos"] ?> Cr&eacute;dito(s)</td>
                    <td class="results"><?= $cadaCredito["fecha_generacion"] ?></td>
                    <td class="results"><?= $cadaCredito["fecha_vence"] ?></td>
                    </tr>					
                    <?php
                }
            ?>		
            </tbody>	
    </table>
</div>