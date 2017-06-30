<!DOCTYPE html>
<html>
    <head>
        <script src="./Javascript/plantilla.js" type="text/javascript"></script>
        <link rel="stylesheet" href="Javascript/sigmin_account.css?v=<?= 6?>">
        <script type="text/javascript" src="Javascript/validarFinder.js?v=3"></script>
        <title>:: SIGMIN :: Mining Properties</title>
        <?php
        include './Plantillas/head.php';
        ?>
    </head>

    <body class="<?= (isset($_SESSION['id_usuario']))?'':'login_bg' ?>">
        <?php // echo $msgAcceso; // include_once("analyticstracking.php") ?>

        <div class="wrapper">
            <?php
            include './Plantillas/menu.php';
            ?>
            <div class="container">
                <div id="creditos" style="display:none"></div>
                <?php
//                            echo $paginaCargue;
                if ($paginaCargue != "reporteAreasAccount.php")
                    include("Vistas/" . $paginaCargue);
                else
                    include("reporteAreasAccount.php");
                ?>
            </div>
            <div class="wrapper_div"></div> <!-- wrapper-->
        </div>

        <?php
        include './Plantillas/foot.php';
        ?>
    </body>
</html>




<!--<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
                <script type="text/javascript" src="Javascript/jquery-1.7.2.min.js"></script>
                <script type="text/javascript" src="Javascript/jquery-ui-1.8.23.custom.min.js"></script>
                <script type="text/javascript" src="Javascript/jquery.tinyscrollbar.min.js"></script>	
                <script type="text/javascript" src="Javascript/validarFinder.js"></script>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
                <link rel="stylesheet" href="Javascript/sigmin_account.css?v=1">
                <script>
                        var controlConfig = 1, divAnterior = "creditos";	

                        function confirmaCreditoMapa(url) {
                                if(confirm("Visualizar el Mapa equivale a 1 Cr\u00E9dito, Desea consumir Cr\u00E9ditos?"))
                                        document.location.href='?pagina=map';
                                else	
                                        document.location.href='?pagina=account';
                        }
                </script>

                <link rel="stylesheet" href="/resources/demos/style.css">
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>		
        

        
        <title>SIGMIN :: Mining Properties</title>
</head>
<body onunload='$("#loadingImage").hide();'>
<img src="Javascript/images/loading_sgm.gif" width="140" height="140" id="loadingImage" style="display:none; top:50%; left:50%; z-index:2000; position:fixed !important; opacity: 0.65;" />
<script>$("#loadingImage").show();</script>
<div id="menuflotante">
        <div>
                <table width="100%">
                        <tr>
                                <td width="160" align="right"><a href="<?= $GLOBALS["sitio"] ?>"><img src="Imgs/sigmin_official.jpg" border="0" title=":: SIGMIN - Mining Properties ::"/></a></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td align="left"><div class="menu-sup"><b>Bienvenido(a) <?= $_SESSION['usr_cred']['nombre'] ?></b></div></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>			
                                <td width="190" align="left"><div><a href="?mnu=creditos" class="menu-horizonal-ps-sm" title="Adquiera más Créditos"><b>Créditos Disponibles</b><br/><?= $_SESSION['usr_cred']['credito'] ?> Cr&eacute;dito(s)</a></div></td>
                                <td width="40" align="left" valign="middle">
                                        <a href="javascript:" onclick="layerConfig('lyConfiguracion')"><img src="Imgs/pinion.png" width="30" height="28" alt="Configuración" title="Configuración"/></a>
                                </td>
                        </tr>
                </table>
        </div>
        <div class="menu-line"></div>
</div>

<div id="lyConfiguracion" class="csConfig" style="display: none;">
        <div class="cfgTitle">Configuraci&oacute;n:</div>
        <hr size="0"/>
                <ul class="csConfigLi" style="width: 180px;">
                        <li class="csConfigLi"><a href="?mnu=datos_basicos" class="csConfigLi">Datos de Usuario</a></li>
                        <li class="csConfigLi"><a href="?mnu=change_passwd" class="csConfigLi">Cambio de Clave</a></li>
                        <li class="csConfigLi"><a href="?mnu=logout" class="csConfigLi"><b>Salir</b></a></li>
                </ul>
        </div>
        
<div id="menu-box">&nbsp;<br/>&nbsp;<br/>&nbsp;</div>

<div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tr>
                        <td valign="top" bgcolor="#ffffff" width="280">
                                <div id="menuflotanteLateral" class="csConfig">
                                        <div>&nbsp;</div>
                                        <div class="cfgTitle" style="margin-left:10px; text-align:left">OPCIONES:</div>
                                        <hr size="0"/>
                                        <ul class="csConfigLi" style="width: 250px;">
                                                <li class="csConfigLi"><a href="javascript:confirmaCreditoMapa('?pagina=map')" class="csConfigLi">Mapa de Propiedades Mineras</a>
                                                <li class="csConfigLi"><a href="?mnu=liberaciones" class="csConfigLi">Liberaciones</a>
                                                <li class="csConfigLi"><a href="?mnu=expedientes" class="csConfigLi">Mis Expedientes</a>
                                                <li class="csConfigLi"><a href="?mnu=prospectos" class="csConfigLi">Mis Prospectos</a>
                                                <li class="csConfigLi"><a href="?mnu=creditos" class="csConfigLi">Mis Cr&eacute;ditos</a>					
                                                <li class="csConfigLi"><a href="?mnu=descargas" class="csConfigLi">Descarga de Shapes</a>
                                                <li class="csConfigLi"><a href="?mnu=contactenos" class="csConfigLi">Zona PQR's</a>
                                                <li class="csConfigLi"><a href="?mnu=datos_basicos" class="csConfigLi">Mis Datos Personales</a>
                                        </ul>
                                </div>
                                &nbsp;
                        </td>
                        <td valign="top">
                                <div>&nbsp;</div>
<?php
//					if($paginaCargue!="reporteAreasAccount.php")
//						include("Vistas/".$paginaCargue);
//					else
//						include("reporteAreasAccount.php");
?>
                                
                        </td>			
                </tr>
        </table>

        <div>&nbsp;</div>

<?= piePagina() ?>	

        <div id="chatonline" onclick="window.open('chat_online/', 'win_chatonline', 'toolbar=no,scrollbars=no,resizable=no,top=60,left=60,width=515,height=415');" title="Chat Online - SIGMIN" style="right: -50px;"></div>
        <script type='text/javascript'>
                $(document).ready(function(){
                        resizesection();
                });
                
                window.onresize = function(event) {
                        resizesection();
                }
                
                function resizesection() {
                        //vpw = $(window).width();
                        vph = $(window).height();
                        $('#chatonline').css({'top': vph-50});
                }
        </script>
        <div id="creditos" style="display:none"></div>
<?php
//		if(@$msgSistema!="") {
?>
                <script>alert('<?= $msgSistema; ?>');</script>
<?php
//		} 
?>
<script>$("#loadingImage").hide();</script>	
</body>
</html>-->
