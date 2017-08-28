


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <?php
        include './Plantillas/head.php';
        ?>

        <title>:: SIGMIN :: Mining Properties</title>
        <!-- <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM'></script> -->



        <script src="http://maps.google.com/maps/api/js?v=3.5&key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM&amp;sensor=false"></script>



        <script src="http://dev.openlayers.org/OpenLayers.js"></script>


        <script type="text/javascript" src="Javascript/jquery-1.7.2.min.js"></script>
        <link href="Javascript/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <script src="Javascript/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
        <link href="Javascript/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>

        <script src="Javascript/mapa2.js?v=<?= $_VERSION ?>" type="text/javascript"></script>
        <style>
            .navbar{
                margin-bottom: 0px !important; 
            }
        </style>
        <script type="text/javascript" src="http://svn.osgeo.org/metacrs/proj4js/trunk/lib/proj4js-compressed.js"></script>
        <script type="text/javascript" src="http://spatialreference.org/ref/epsg/31467/proj4js/"></script>		

        <script type="text/javascript">

            var LIMIT_INFERIOR = 0;
            var LIMIT_SUPERIOR = 500000;
        </script>
        <script src="Javascript/mapa_control2.js?v=<?= $_VERSION ?>" type="text/javascript"></script>      

        <script type="text/javascript">



            function controlEnter(e) {
                if (e.keyCode == 13) {
                    validarBusqueda();
                    return false;
                }
            }

            $(document).ready(function () {
                /* Aquí podría filtrar que controles necesitará manejar,
                 * en el caso de incluir un dropbox $('input, select');
                 */
                tb = $('#txtBusqueda');

                if ($.browser.mozilla) {
                    $(tb).keypress(controlEnter);
                } else {
                    $(tb).keydown(controlEnter);
                }
            });
        </script>
        <script type="text/javascript" src="Javascript/jquery.qtip-1.0.0-rc3.min.js"></script>
    </head>	
    <body onLoad="init()">	

        <?php
        include './Plantillas/menu.php';
        ?>

        <div id="info" >            

            <div id="infoControl" >

                <div  id="div_min">
                    <i id="ico_min" class="fa fa-angle-double-left" aria-hidden="true"></i>    
                </div>
<!--                <div id="div_ocultar">
                    <i id="ico_ocultar" class="fa fa-times" aria-hidden="true"></i>      
                </div>-->
            </div>
            <div id="info_sc">

            </div>
        </div>
        <?php
        if ($ope != 'directorio') {            
            ?>
            <div id="map" class="mapa_login"></div>            
            <?php
        }else{
            include './directorio.php';
        }
        if (in_array($ope, ['ingresar', 'registro', 'olvide'])) {
            ?>
            <div class="contenedor">
                <div class="container">
                    <div class="row">

                        <div class="<?= (in_array($ope, ['registro', 'olvide'])) ? "col-lg-8" : "col-lg-5" ?>" style="float: none;margin: 0 auto">
                            <?php
                            include './ingresar.php';
                            ?>
                        </div>

                    </div>
                </div>
            </div>

            <?php
        }
        ?>

        <?php
        if (!empty($msgError) && $msgError != "")
            echo $msgError;
        ?>	
        <script>
            $('input[placeholder], textarea[placeholder]').placeholder();
        </script>	
        <img src="Javascript/images/loading_sgm.gif" width="140" height="140" id="loadingImage" style="display:none; top:50%; left:50%; z-index:2000; position:fixed !important; opacity: 0.65;" />	

        <?php
        include './Plantillas/foot_1.php';
        ?>
    </body>
</html>
