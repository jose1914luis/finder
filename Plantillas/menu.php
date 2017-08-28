
<nav class="navbar navbar-default">
    <div class="container-fluid">                    

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=".">
                <img class="logosgm" alt="SIGMIN" src="Javascript/images/logo3.png">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <form class="navbar-form navbar-left" name="searchWords">
                <div class="input-group buscador">
                    <input id="txtBusqueda" type="text" class="form-control" placeholder="Palabra Clave...">                    
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="validarBusqueda();" type="button"><span class="glyphicon glyphicon-search"></span><span style="visibility: hidden">.</span></button>
                    </span>                    
                </div>  
            </form>            
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['id_usuario'])) { ?>

                    <li><a href="?mnu=creditos"> Creditos: <?= $_SESSION['usr_cred']['credito'] ?>$</a></li>
                    <?php if ($_SESSION["pagina"] != "map") { ?>
                        <li><a href="javascript:confirmaCreditoMapa('?pagina=map')"> Mapa</a></li>
                    <?php } if ($_SESSION["pagina"] == "map") { ?>                     

                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Análisis <b class="caret"></b></a>
                            <ul class="dropdown-menu">                               
                                <li class="menu-item dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Polígono <i class="fa fa-square-o" aria-hidden="true"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="menu-item "><a href="javascript:"  id="poligono" onClick="Pros_Open('generarArea');toggleControl(this);" >Dibujar <i class="fa fa-pencil" aria-hidden="true"></i></a></li>
                                        <li class="menu-item "><a href="javascript:" onClick="Pros_Open('generarArea');mostrardiv('freeGeneratorArea_coordinates')">Coordenadas <i class="fa fa-map-o" aria-hidden="true"></i></a></li>                                       
                                    </ul>
                                </li>
                                 <li class="menu-item dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Perímetro <i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="menu-item "><a href="javascript:"  id="area" value="point"onclick="Pros_Open('Perimetral');toggleControl(this);">Ubicar Punto <i class="fa fa-location-arrow" aria-hidden="true"></i></a></li>
                                        <li class="menu-item "><a href="javascript:" onclick="Pros_Open('Perimetral');mostrardiv('point_coordinates')">Coordenadas <i class="fa fa-map-o" aria-hidden="true"></i></a></li>                                       
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    <?php } ?>
                    <li role="presentation" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Propiedades Mineras <span class="caret"></span></a>
                        <ul class="dropdown-menu">                                    
                            <li><a href="?mnu=prospectos">Mis Prospectos</a></li>
                            <li><a href="?mnu=expedientes">Mis Expedientes</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?mnu=liberaciones">Liberaciones</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?mnu=descargas">Shapes</a></li>
                        </ul>
                    </li>
                    <li role="presentation" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cuenta<span class="caret"></span></a>
                        <ul class="dropdown-menu">                                    
                            <li><a href="?mnu=creditos">Creditos</a></li>
                            <li><a href="?mnu=datos_basicos">Datos Personales</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?mnu=logout">Logout <i class="fa fa-power-off padding-left-ten-px red-text"></i></a></li>
                        </ul>
                    </li>


                <?php } else { ?>
                    <li><a href=".?ope=directorio">Minerales <span class="glyphicon glyphicon-list-alt"></span></a></li> 
                    <li><a href=".?ope=ingresar">Ingresar <span class="glyphicon glyphicon-user"></span></a></li> 
                    
                        <?php } ?>    

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>