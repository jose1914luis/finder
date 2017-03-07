<?php


	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	
	require_once("Modelos/ReportGenerator.php");
	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ReportGenerator();
	$tabla 		= "";
	

	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	

	if(!empty($_POST)) {	
		$listadoSolicitudes = $reporte->selectSolicitudesConsultas($_POST["txt_CodigoExpediente"], $_POST["txt_Mineral"], $_POST["txt_Municipio"], $_POST["txt_Departamento"], $_POST["txt_Propietario"], $_POST["dt_RadicaDesde"], $_POST["dt_RadicaHasta"], $_POST["selModalidad"], $_POST["selEstadoJuridico"]);
		
		if(!empty($listadoSolicitudes)){		
			$nroSolicitudes = sizeof($listadoSolicitudes);
			$tabla ="<h3>Total Solicitudes: $nroSolicitudes</h3><table border='1'>";
			$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";
		
			foreach($listadoSolicitudes[0] as $k=>$v)
				$tabla .= "<td align='center'><b>".$k."</b></td>";
			$tabla .= "</tr>";	
			
			for($i=0;$i<$nroSolicitudes;$i++) {
				if(!empty($listadoSolicitudes[$i]["centroide"]))
					$enlace = "<a href='#' onclick=\"consultarURL('".$listadoSolicitudes[$i]["placa"]."','SOLICITUD')\">[o]</a>";
				else
					$enlace = "&nbsp;";	
				$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoSolicitudes[$i] as $k=>$v)
					$tabla .= "<td>".utf8_decode($v)."</td>";
				$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoSolicitudes[$i]["placa"]."&tipoExpediente=SOLICITUD' target='_blank' >Report</a></td>";
				$tabla .= "</tr>";	
			}	
			$tabla .= "</table>";	
		}
		
		$listadoTitulos = $reporte->selectTitulosConsultas($_POST["txt_CodigoExpediente"], $_POST["txt_Mineral"], $_POST["txt_Municipio"], $_POST["txt_Departamento"], $_POST["txt_Propietario"], $_POST["dt_OtorgaDesde"], $_POST["dt_OtorgaHasta"], $_POST["selModalidad"], $_POST["selEstadoJuridico"]);
		
		if(!empty($listadoTitulos)){
			$nroTitulos = sizeof($listadoTitulos);
			$tabla .="<p><hr size='1'><h3>Total T&iacute;tulos: $nroTitulos</h3><table border='1'>";
			$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";

			foreach($listadoTitulos[0] as $k=>$v)
				$tabla .= "<td align='center'><b>".$k."</b></td>";
			$tabla .= "</tr>";	
			
			for($i=0;$i<$nroTitulos;$i++) {
				if(!empty($listadoTitulos[$i]["centroide"]))
					$enlace = "<a href='#' onclick=\"consultarURL('".$listadoTitulos[$i]["placa"]."','TITULO')\">[o]</a>";
				else
					$enlace = "&nbsp;";	
				$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoTitulos[$i] as $k=>$v)
					$tabla .= "<td>".$v."</td>";
				$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoTitulos[$i]["placa"]."&tipoExpediente=TITULO' target='_blank' >Report</a></td>";
					
				$tabla .= "</tr>";	
			}		
			$tabla .= "</table>";	
		}		
/*	
		$resultado = $empresa->insertAll($_POST);
		if($resultado != "ok")
			$msgError = "<script>alert('Error durante el proceso de guardado de la Empresa {$_POST["emp_nombre"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('La Empresa {$_POST["emp_nombre"]} a sido almacenada correctamente')</script>";
*/
	}

	
?>	

<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	</head>
	<body>
		<p>
		<form action="prospect.report.consulta.win.c.php" method="POST" target="pop">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;REPORT&nbsp;&nbsp;&nbsp;GENERATOR&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td>C&oacute;digo del Expediente:</td>
				<td><input type="text" name="txt_CodigoExpediente"></td>
			</tr>
			<tr>
				<td>Estado Jur&iacute;dico del Expediente:</td>
				<td>
				<select name="selEstadoJuridico">
					<option value="ALL">Todos los Estados
				<?php
					foreach( $reporte->getEstadosJuridicos() as $cadaEstado)
						echo "<option value='{$cadaEstado["estado_juridico"]}'>{$cadaEstado["estado_juridico"]}";
				?>
				</select>
				
				</td>
			</tr>						
			<tr>
				<td>Modalidad del Expediente:</td>
				<td>
				<select name="selModalidad">
					<option value="ALL">Todas las Modalidades
				<?php
					foreach( $reporte->getModalidades() as $cadaModalidad)
						echo "<option value='{$cadaModalidad["modalidad"]}'>{$cadaModalidad["modalidad"]}";
				?>
				</select>
				
				</td>
			</tr>			
			<tr>
				<td>Mineral:</td>
				<td><input type="text" name="txt_Mineral" size=45></td>
			</tr>
			<tr>
				<td>Departamento:</td>
				<td>
					<input type="text" name="txt_Departamento" size=65>			
				</td>
			</tr>
			<tr>
				<td>Municipio:</td>
				<td>
					<input type="text" name="txt_Municipio" size=65>			
				</td>
			</tr>

			<tr>
				<td>Propietario:</td>
				<td><input type="text" name="txt_Propietario" size=60></td>
			</tr>

			<tr>
				<td>Fecha Radicaci&oacute;n :</td>
				<td>
					Desde: <input type="text" name="dt_RadicaDesde"> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Hasta: <input type="text" name="dt_RadicaHasta">				
				</td>
			</tr>

			<tr>
				<td>Fecha Otorgamiento :</td>
				<td>
					Desde: <input type="text" name="dt_OtorgaDesde"> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Hasta: <input type="text" name="dt_OtorgaHasta">				
				</td>
			</tr>
			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="button" value="Consultar" onclick="window.open('', 'pop', 'width=800, height=700, resizable=yes, scrollbars=yes'); document.forms[0].submit();return false;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" value="Eliminar Datos">
					<hr size=1/>
				</td>
			</tr>				
			
		</table>
		<input name="ejecutaBusqueda" type="hidden" id="ejecutaBusqueda" value="YES">
		</form>
		<?php
			if($tabla != "") echo $tabla;
		
			echo $msgError;
		?>
	</body>
</html>
