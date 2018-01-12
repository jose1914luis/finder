<script>
    function enviarForm() {
        if (!validarDatosUsuario())
            return 0;
        document.frmAdminUser.submit();
        }

    // selecciona mpio dado departamento
    function seleccionarMunicipio(idDepto) {
        $("#selMunicipio").load('viewMunicipios.php?idDepto=' + idDepto);
    }
    ;

    // seleccion de acuerdo al tipo de documento
    function cambioCrazon(idTipoDocumento) {
        var txtRazon = "";
        if (idTipoDocumento != 5) {
            txtRazon += '<input type="text" name="txtNombre" size="25" placeholder="Nombres" value="<?= $listaForm["nombres"] ?>" <?= $readonly ?>>&nbsp;&nbsp;';
            txtRazon += '<input type="text" name="txtApellido" size="25" placeholder="Apellidos" value="<?= $listaForm["apellidos"] ?>" <?= $readonly ?>>';
        } else {
            txtRazon += '<input type="text" name="txtNombre" size="55" placeholder="Razón Social" value="<?= $listaForm["razon_social"] ?>" <?= $readonly ?>>';
            txtRazon += '<input type="hidden" name="txtApellido" value=""></div>';
        }
        $("#crazon").html(txtRazon);
    }
    ;
</script>



<div id="table-wrapper">
    
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading"><h4>Datos de Usuario</h4></div>
        <div class="panel-body">

            <form class="form-horizontal" name="frmAdminUser" method="post" action="?mnu=datos_basicos<?php echo $rest = (@$_GET["credits"] == 1) ? "&credits=1" : "" ?>">


                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipo de Documento:*</label>
                    <div class="col-sm-3">
                        <select class="form-control" type="text" id="selTipoDocumento" name="selTipoDocumento" onChange="cambioCrazon(this.value)">
                            <?php
                            if ($readonly != "")
                                echo "<option value='{$listaForm["id_tipo_documento"]}' selected>{$listaForm["tipo_documento"]}</option>";
                            else {
                                ?>
                                <option value="0">Seleccione Tipo de Documento</option>
                                <?php
                                foreach ($identificacion->selectAll() as $cadaDocumento) {
                                    ?>
                                    <option value=<?= $cadaDocumento["id"] ?>> <?= ($cadaDocumento["nombre"]) ?> </option>
                                    <?php
                                }
                            }
                            ?>					
                        </select>	
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">N&uacute;mero de Documento:*</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="txtDocumento" size="45" value="<?= $listaForm["numero_documento"] ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Nombre/Raz&oacute;n Social:*</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="txtNombre" size="25" placeholder="Nombres" value="<?= $listaForm["nombres"] ?>" <?= $readonly ?>>&nbsp;&nbsp;            
                    </div>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="txtApellido" size="25" placeholder="Apellidos" value="<?= $listaForm["apellidos"] ?>" <?= $readonly ?>>
                    </div>

                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Fecha de Nacimiento:*</label>
                    <div class="col-sm-3">
                        <input class="form-control" name="txtFechaNacimiento" type="text" size="25" value="<?= $listaForm["fecha_nacimiento"] ?>" placeholder="DD/MM/AAAA"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Correo Electr&oacute;nico:*</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="buyerEmail" type="text" size="60" value="<?= $listaForm["correo_electronico"] ?>" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Tel&eacute;fono:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="txtTelefono" size="25"  value="<?= $listaForm["telefono"] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">N&uacute;mero Celular:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="txtCelular" size="25" value="<?= $listaForm["celular"] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Departamento:*</label>
                    <div class="col-sm-3">
                        <select class="form-control" type="text" id="selDepartamento" name="selDepartamento" onChange="seleccionarMunicipio(this.value)">
                            <?php
                            if ($readonly != "")
                                echo "<option value='{$listaForm["id_departamento"]}' selected>{$listaForm["departamento"]}</option>";
                            ?>				
                            <option value="0">Seleccione Departamento</option>

                            <?php
                            foreach ($deptos->selectAll() as $cadaDepto) {
                                ?>
                                <option value=<?= $cadaDepto["id"] ?>> <?= ($cadaDepto["nombre"]) ?> </option>
                                <?php
                            }
                            ?>					
                        </select>	
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Municipio:*</label>
                    <div class="col-sm-3">
                        <select class="form-control" type="text" id="selMunicipio" name="selMunicipio">
                            <?php
                            if ($readonly != "")
                                echo "<option value='{$listaForm["id_municipio"]}' selected>{$listaForm["municipio"]}</option>";
                            ?>							
                            <option value="0">Seleccione Municipio</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Dirección*</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="txtDireccion" size="60"  value="<?= $listaForm["direccion"] ?>">
                    </div>
                </div>

                <center>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" onclick="cambiarContra()">Cambiar contraseña</button>
                    </div>
                    <div id="contra">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Contraseña Actual</label>
                            <div class="col-sm-5">
                                <input class="form-control" type="password" id="txtContra" name="claveOld" size="10"  >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Nueva contraseña</label>
                            <div class="col-sm-5">
                                <input class="form-control" type="password" id="txtContra2" name="claveNew" size="10" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Repita la nueva contraseña</label>
                            <div class="col-sm-5">
                                <input class="form-control" type="password" id="txtContra3" name="textContra3" size="10" >
                            </div>
                        </div>
                    </div>


                </center>


                <center><input class="btn btn-success"type="button" name="type" id="cambioClave" title="Aplicar Cambio" value="Guardar Información" onClick="enviarForm()"/></center>
                <br>
            </form>	
        </div>
    </div>


</div>
