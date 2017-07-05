
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
                <div class="input-group">
                    <input id="txtBusqueda" type="text" class="form-control" placeholder="Palabra Clave...">                    
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="validarBusqueda();" type="button"><span class="glyphicon glyphicon-search"></span><span style="visibility: hidden">.</span></button>
                    </span>                    
                </div>  
            </form>            
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['id_usuario'])) { ?>

                    <li><a href="#"> Creditos: <?= $_SESSION['usr_cred']['credito'] ?>$</a></li>
                    <li><a href="javascript:confirmaCreditoMapa('?pagina=map')"> Mapa</a></li>
                    <li role="presentation" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Propiedades Mineras <span class="caret"></span></a>
                        <ul class="dropdown-menu">                                    
                            <li><a href="?mnu=prospectos">Mis Prospectos</a></li>
                            <li><a href="?mnu=expedientes">Mis Expedientes</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?mnu=liberaciones">Liberaciones</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Shapes</a></li>
                        </ul>
                    </li>
                    <li role="presentation" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cuenta<span class="caret"></span></a>
                        <ul class="dropdown-menu">                                    
                            <li><a href="?mnu=creditos">Creditos</a></li>
                            <li><a href="?mnu=datos_basicos">Datos Personales</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?mnu=logout">Salir</a></li>
                        </ul>
                    </li>


                <?php } else { ?>
                    <li><a href=".?ope=ingresar">Ingresar <span class="glyphicon glyphicon-user"></span></a></li> 
                        <?php } ?>    

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>