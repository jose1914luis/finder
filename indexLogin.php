<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="Javascript/jquery.placeholder.min.js"></script>   
        <script src="Javascript/procesarUsrLogin.js?v=<?= time() ?>"></script>   
        <link rel="stylesheet" href="Javascript/login.css?v=<?= time() ?>" type="text/css" media="all" />

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <title>:: SIGMIN :: Mining Properties</title>
       
    </head>



    <body class="login_bg">
        <?php // include_once("analyticstracking.php") ?>

        <div class="wrapper">
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
                        <a class="navbar-brand" href="#">
                            <img class="logosgm" alt="SIGMIN" src="Javascript/images/logo_login.png" width="80%">
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                        <form class="navbar-form navbar-left">
                            <div class="input-group input-group-lg buscador">
                                <input type="text" class="form-control" placeholder="Palabra Clave...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                </span>
                            </div><!-- /input-group -->
                        </form>

                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#"> Creditos: 1000<span class="glyphicon glyphicon-usd"></span></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Propiedades Mineras <span class="caret"></span></a>
                                <ul class="dropdown-menu">                                    
                                    <li><a href="#">Mis Prospectos</a></li>
                                    <li><a href="#">Mis Expedientes</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Liberaciones</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Shapes</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Ingresar <span class="glyphicon glyphicon-user"></span></a></li>                                                        
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>

        </div>
        <!--
        <div class="login_box">
            <div>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/></div>
            <center><div class="logo_login">Sigmin</div></center>
            <div>&nbsp;<br/>&nbsp;</div>
            <div>
                <form method="post">
                    <div class="form-group">                    
                        <input type="text" placeholder="Correo Electrónico" id="username" name="username" class="form-control"  required/>
                    </div>
                    <div class="form-group">                        
                        <input type="password"  name="password" id="password"  placeholder="Clave" class="form-control"  required/>
                    </div>

                    <div>                        
                        <center>
                            <a href="javascript:" class="forgot" onclick="mostrardiv('lyAdminUserPwd')"><b>Olvid&oacute; su Contrase&ntilde;a?</b></a><br/>								                    
                            <div class="redimir">Redime tu Bono<br/></div>
                            <div class="form-group form-group-sm">                        
                                <input type="text"  name="codigo_uso" id="codigo_uso"  placeholder="Ingresa tu Código" title="Solo si posee cupón de cortesía de SIGMIN" class="form-control"/>                    	
                            </div>
                            <br>
                            <input type="submit" class="btn btn-success" value="  INGRESAR  " /><br>
                            <b><a href="javascript:" class="registernow" data-toggle="modal" data-target="#myModal">&nbsp;&nbsp;Reg&iacute;strese Gratis&nbsp;&nbsp;</a></b>
                        </center>
                    </div>
                </form>
            </div>	
            <div>&nbsp;<br/>&nbsp;</div>

        </div>

        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Crear Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <form name="frmAdminUser" method="post" action="index.php" class="form-horizontal">

                            <div class="form-group">    
                                <label class="col-sm-4 control-label">N&uacute;mero de Documento:</label>    
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="txtDocumento">
                                </div>

                            </div>
                            <div class="form-group">    
                                <label class="col-sm-4 control-label">Nombre:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="txtNombre" size="30">
                                </div>
                            </div>
                            <div class="form-group">    
                                <label class="col-sm-4 control-label">Correo Electr&oacute;nico:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="txtEmail" size="30">
                                </div>
                            </div>
                            <div class="form-group">    
                                <label class="col-sm-4 control-label">Contrase&ntilde;a:</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="txtPassword">
                                </div>
                            </div>
                            <div class="form-group">    
                                <label class="col-sm-4 control-label">Repetir Contrase&ntilde;a:</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="txtPassword2">
                                </div>
                            </div>
                           <input type="hidden" name="captcha2" value="captcha2">				
                            </center>
                        </form>                        

                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" value="Crear Usuario" onclick="validarCreacionUsr();" />                        
        <?php // if (empty($_GET["register"])) { ?>
                            <input type="button" value="Cancelar" class="btn btn-default" data-dismiss="modal">
        <?php // } ?>
                    </div>
                </div>
            </div>
        </div>



        <form name="frmForgetPwd" method="post">
            <div id="lyAdminUserPwd" class="adminUser" style="display: none;">	
                <div class="titleAdminUser">    
                    <center><strong>:: &nbsp;&nbsp;&nbsp;Recuperar Contrase&ntilde;a&nbsp;&nbsp;&nbsp; ::</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:" onclick="cerrar('lyAdminUserPwd')" title="Cerrar ventana" style="color: #ffffff; text-decoration: none">[X]</a></center></div>
                <div>
                    <table border="0" align="center" width="95%" class="tableUser">	
                        <tr>
                            <td colspan="2">
                                <p>Ingrese el Correo Electr&oacute;nico con el cual usted tiene registrada la cuenta, all&iacute; le será suministrada una clave aleatoria para acceso</p>
                                <p>Recuerde utilizar la opción <b><i>"Account"</i></b> para asignar una nueva clave de acceso.</p>
                            </td>
                        </tr>	
                        <tr>
                            <td>Correo Electr&oacute;nico:</td>
                            <td><input type="text" name="txtEmail" size="30"> *</td>
                        </tr>					
                        <tr>
                            <td colspan="2"><hr size="0"></td>
                        </tr>						
                        <input type="hidden" name="captcha" value="captcha">
                                                                
                        <tr>
                            <td colspan="2">
                        <center>
                            <input type="button" value="Recuperar Contraseña" onclick="validarRecuperarUsr()"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="button" value="Cancelar" onclick="cerrar('lyAdminUserPwd')">
                        </center>
                        </td>
                        </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr size="0"></td>
                        </tr>						
                    </table>
                </div>
            </div >
        </form>


        <script>
            $('input[placeholder], textarea[placeholder]').placeholder();
        </script>
        -->
        <?php
//        if (!empty($_GET["register"]) && strlen($_GET["register"]) == 15)
//            echo "<script>mostrardiv('lyAdminUser')</script>";
        ?>

    </body>
</html>
