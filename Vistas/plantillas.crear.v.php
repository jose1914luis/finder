<?php

	// Impresión de la lista de tipos de datos
	$menuTiposDatos = "<option value='0'>Seleccione el tipo de dato ... </option>";
	foreach($listaTipoDatos as $cadaTipoDato)
		$menuTiposDatos .= "<option value='".utf8_decode($cadaTipoDato["nombre"])."'>".utf8_decode($cadaTipoDato["nombre"])."</option>";	
	
?>


		<script>
		// Definición de variable global
		indexDiv=0;

		function nameForm() {
			document.write('<table border=0>')
			document.write('<tr><td><b>Nombre de la Plantilla: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td><input type="text" name="nombrePlantilla" value="" size="70"></td></tr>');
			document.write('<tr><td><b>Clasificaci&oacute;n: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td><input type="text" name="clasificacionPlantilla" value="" size="80"> &nbsp; &nbsp; <input type="button" value="+" size="3" title="Agregar Nueva Clasificación"></td></tr>');
			document.write('<tr><td><b>Breve detalle de la Plantilla: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td><textarea  rows="3" cols="65" name="detallePlantilla"></textarea></td></tr>');
			document.write('</table>');
			indexDiv++;
		}

		function getCode() {
			alert("HTML CODE: "+$("#div_main").html());
		}

		function delDiv(nameDiv) {
			$(nameDiv).remove();	
		}

		
		function insertDiv() {
			txt = "	<div id='campo"+indexDiv+"' style='border:solid; border-color:#CCCCCC; border-width:thin;' >" +
						"<div style='background:#FFFFCC'>Campo N&uacute;mero "+indexDiv+": &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:delDiv(\"#campo"+indexDiv+"\");'>[X]</a></div><hr size='0'>" +
						"<table border=0>" +
						"	<tr><td><b>Nombre del Campo: </b></td><td><input type='text' name='fieldName["+indexDiv+"]' size='30'><br></td><tr>" +
						"	<tr><td><b>Texto de Ayuda: </b></td><td><input type='text' name='fieldHelp["+indexDiv+"]' size='50'><br>" +
						"	<tr><td><b>Es Obligatorio?  </b></td><td>&nbsp;&nbsp;&nbsp;SI<input name='fieldReq["+indexDiv+"]' type='radio' value='1'/>&nbsp;&nbsp;&nbsp;No<input name='fieldReq["+indexDiv+"]' type='radio' value='0'  checked /><br /></td></tr>" +
						"	<tr><td><b>Tipo de Campo: </b></td>" +
						" 		<td><select name='fieldType["+indexDiv+"]' onchange='habilitaLista(this.value, "+indexDiv+")'>" +
						"   	<?php echo $menuTiposDatos ?>" +
						"	</select></td></tr>" +
						"	<tr><td><b>Opciones de Lista<br>(separarar por <i>'Enter'</i>): </b></td>" + 
						"		<td><textarea rows='5' cols='35' name='listOptions["+indexDiv+"]' id='listOptions_"+indexDiv+"' disabled></textarea></td></tr>" + 
						"</table>" +
						"</div>";
			$("#div_main").append(txt);
			indexDiv++;
		}		
		
		function habilitaLista(tipoDato, indice) {
			buscar = /LISTA/;
			
			if(buscar.test(tipoDato)) {
				eval("document.forms[0].listOptions_"+indice+".disabled = false");
			} else {
				eval("document.forms[0].listOptions_"+indice+".disabled = true");
				eval("document.forms[0].listOptions_"+indice+".value = ''"); 				
			}			
		}			
		
		</script>

		<form name="frm01" method="POST">
			<table border="0" align="center" width="100%">
				<tr>
					<td align="center">
						<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color='black'>::&nbsp;&nbsp;&nbsp;&nbsp;GENERAR&nbsp;&nbsp;&nbsp;PLANTILLA&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
					</td>
				</tr>
				<tr>
					<td>
					<input type="hidden" name="operacionForm" value="plantillas.crear"/>
					<div id="div_main">
						<div id="div0"><script> nameForm(); </script>
						</div>
					</div>
						<hr>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value=" [+] Agregar Campo" onclick="insertDiv()" />
						
						<hr>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Guardar Formulario" />
						<hr>
					</td>
				</tr>
			</table>
		</form>
		
<?php
		// mensaje de resultado de operaciones de creacion de plantillas
		if(!empty($strMsg)) echo $strMsg;
?>

