<!-- Latest compiled and minified CSS -->
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>

<!-- Optional theme -->
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>

<!-- Latest compiled and minified JavaScript -->
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>


"<div class="panel panel-danger">
    "<div class="panel-heading">Requerimiento N&uacute;mero</div>"+
    "<div class="panel-body">
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Tipo Requerimiento: </label>"+
            "<div class='col-sm-3'>                
                "<input type='text' class='form-control'  id='codigoExpediente' name='codigoExpediente' size='20' onchange='loadDocRequeridos(this.value)' value='<?= $codigoExp ?>'/>
            </div>"+
        </div>"+
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Destinatario: </label>"+
            "<div class='col-sm-3'>
                "<input type='text' class='form-control'  id='nroRadicado' name='nroRadicado' size='30'/>
            </div>"+
        </div>"+
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Fecha Requerimiento:</label>"+
            "<div class='col-sm-3'>
                "<input type='text' class='form-control'  id='fechaRadicado' name='fechaRadicado' size='20' placeholder='dd/mm/yyyy [hh24:mi]'/> "+				
            </div>"+
        </div>"+        
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Rango</label>"+
            "<div class='col-sm-3'>
                <select class="form-control">
                    <option>Dias</option>
                    <option>Meses</option>
                </select>
            </div>"+
        </div>"+        
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Fecha Vence</label>"+
            "<div class='col-sm-3'>
                "<input type='text' class='form-control'  id='fechaRadicado' name='fechaRadicado' size='20' placeholder='dd/mm/yyyy [hh24:mi]'/> "+				
            </div>"+
        </div>"+
        "<div class='form-group'>
            "<label class='control-label col-sm-3' for='email'>Detalle Requerimiento:</label>"+
            "<div class='col-sm-3'>
                <textarea class="form-control"></textarea>
            </div>"+
        </div>"+
    </div>"+
</div>"+