


<?php
if ($ope == 'olvide') {
    ?>

    <div class="login_box">
        <form name="frmForgetPwd" method="post" class="form-horizontal">

            <div class="titleAdminUser">    
                <center><strong>Recuperar Contrase</strong>
                </center>
            </div>
            <div>
                <p>Ingrese el Correo Electr&oacute;nico con el cual usted tiene registrada la cuenta, all&iacute; le será suministrada una clave aleatoria para acceso</p>
                <p>Recuerde utilizar la opción <b><i>"Account"</i></b> para asignar una nueva clave de acceso.</p>
            </div>

            <div class="form-group">    
                <label class="col-lg-4 control-label">Correo Electrónico</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" name="txtEmail" size="30">
                </div>
            </div>
            <input type="hidden" name="captcha" value="captcha">
            <center>
                <div class="g-recaptcha" data-sitekey="6Le4hSYUAAAAAJQNqJHmt4WYAcoceHhJJc9jcuN8"></div><br>
                <input type="button" class="btn btn-success" value="Recuperar Contraseña" onclick="validarRecuperarUsr()">
            </center>
        </form>
    </div>



    <?php
} elseif ($ope == 'registro') {
    ?>
    <div class="login_box">

        <form name="frmAdminUser" method="post" action="index.php" class="form-horizontal">
            <div class="titleAdminUser">    
                <center><strong>Crear Cuenta</strong>
                </center></div>
            <br/>
            <div class="form-group">    
                <label class="col-lg-3 control-label">N&uacute;mero de Documento:</label>    
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="txtDocumento">
                </div>

            </div>
            <div class="form-group">    
                <label class="col-lg-3 control-label">Nombre Completo:</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" name="txtNombre" size="30">
                </div>
            </div>
            <div class="form-group">    
                <label class="col-lg-3 control-label">Correo Electr&oacute;nico:</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" name="txtEmail" size="30">
                </div>
            </div>
            <div class="form-group">    
                <label class="col-lg-3 control-label">Contrase&ntilde;a:</label>
                <div class="col-lg-4">
                    <input type="password" class="form-control" name="txtPassword">
                </div>
            </div>
            <div class="form-group">    
                <label class="col-lg-3 control-label">Repetir Contrase&ntilde;a:</label>
                <div class="col-lg-4">
                    <input type="password" class="form-control" name="txtPassword2">
                </div>
            </div>
            <input type="hidden" name="captcha2" value="captcha2">

            <center>
                <div class="g-recaptcha" data-sitekey="6Le4hSYUAAAAAJQNqJHmt4WYAcoceHhJJc9jcuN8"></div><br>
                <input type="button" class="btn btn-success" value="Crear Cuenta" onclick="validarCreacionUsr();" />
            </center>            
        </form>  
        <br/>
    </div>

    <?php
} else {
    ?>

    <div class="login_box">
        <div class="titleAdminUser">    
            <center><strong>Ingresar</strong>
            </center></div>
        <br/>
        <div>
            <form class="form-horizontal" method="post">
                <div class="form-group">     
                    <div class="col-lg-12">
                        <input type="text" placeholder="Correo Electrónico" id="username" name="username" class="form-control"  required/>
                    </div>

                </div>
                <div class="form-group">                        
                    <div class="col-lg-7">
                        <input type="password"  name="password" id="password"  placeholder="Clave" class="form-control"  required/>
                    </div>
                    <div class="col-lg-5">                        
                        <a href=".?ope=olvide" class="forgot">Olvid&oacute; su Contrase&ntilde;a?</a><br/>
                    </div>
                </div>               

                <div class="form-group">                        
                    <div class="col-lg-4"> 
                        <a href=".?ope=registro" class="forgot" >&nbsp;&nbsp;Reg&iacute;strese Gratis&nbsp;&nbsp;</a>
                    </div>
                    <div class="col-lg-4">                        								                                           
                        <input type="submit" class="btn btn-success" value="Ingresar" /><br>
                    </div>
                </div>
            </form>
        </div>	
    </div>

    <?php
} 

