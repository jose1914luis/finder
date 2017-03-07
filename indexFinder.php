<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>:: SIGMIN :: Mining Properties</title>
		<!-- <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM'></script> -->

 <link rel="stylesheet" href="Javascript/style_theme.css" type="text/css">
 <link rel="stylesheet" href="Javascript/styleV3.css" type="text/css">
 
 
 <script src="http://maps.google.com/maps/api/js?v=3.5&key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM&amp;sensor=false"></script>



		<script src="http://dev.openlayers.org/OpenLayers.js"></script>
	
		
		<link rel="stylesheet" href="Javascript/base.css" type="text/css" media="all" />
		<link rel="stylesheet" href="Javascript/jquery-ui.css" type="text/css" media="all" />
		<link rel="stylesheet" href="Javascript/ui.theme.css" type="text/css" media="all" />
		<script src="Javascript/validarFinder.js"></script>
		<link rel="stylesheet" href="Javascript/css/website.css" type="text/css" media="all"/>
        <script type="text/javascript">
		 function mover_capas(){ 		
				if(document.getElementById('capas').title=="inicio"){
					$('#capas').animate({
  					right: "-50px"
					}, 500 ); 
					document.getElementById('capas').title="segundo"; 
				} else {
					$('#capas').animate({
  					right: "-300px"
					}, 500 );  
					document.getElementById('capas').title="inicio"; 
				}
			}
			function mover_tools(){ 	
				if(document.getElementById('tools').title=="inicio"){
					$('#tools').animate({
  					right: "-45px"
					}, 500 ); 
					document.getElementById('tools').title="segundo"; 
				}else{
					$('#tools').animate({
  					right: "-245px"
					}, 500 );  
					document.getElementById('tools').title="inicio"; 
				}
			}
			
			function mostrardiv(division) 	{ 	div = document.getElementById(division);	div.style.display = "";		};
			function cerrar(division) 		{	div = document.getElementById(division);	div.style.display="none";	};
			
			function ajaxFileUpload()	{			
				$.ajaxFileUpload(
				{
					url:'viewLoadCoordinates.php',
					secureuri:false,
					fileElementId:'fileToUpload',
					dataType: 'execute', 
					data:{sistemaOrigen: document.forms[1].selGeoSystem.value},
					success: function (data, status)			{
						if(typeof(data.error) != 'undefined')	{
							if(data.error != '') {	alert(data.error);	}
							else	{	alert(data.msg);				}
						}
					},
					error: function (data, status, e)	{	alert(e);	}
				}
				)
				
				//cerrar('freeGeneratorArea_coordinates');
				return false;
			}

			function pointAddedCoords() {
				if(document.frmCoordinatesPoint.selGeoSystem.value == "0") {
					alert('Seleccione un Sistema de Coordenadas');
					return 0;
				}
					
				stringCoords = "POINT(" + document.frmCoordinatesPoint.coordX.value.trim() + " " + document.frmCoordinatesPoint.coordY.value.trim() + ")";
			
				drawControls["point"].activate();														
				var coordenadasRAC = "";			
				$.post('viewEvalBuffer.php', { CoordenadasVMC : stringCoords, radioAccion: document.forms["neighbor"].txtRadio.value, sistema_origen: document.frmCoordinatesPoint.selGeoSystem.value}, function(resp) {
						if(resp!="") 
							eval(resp);
						else
							alert("No hay retorno de informacion");
					});				
				CONTAR_POLY ++;						
				drawControls["point"].deactivate();														
			};			
			
			function ConvertDMSToDD(degrees, minutes, seconds, direction) {
				var dd = degrees + minutes/60 + seconds/(60*60);

				if (direction == "S" || direction == "W") {
					dd = dd * -1;
				} // Don't do anything for N or E
				return dd;
			}
			
			function procesarGMS(coordenada) {
				if(coordenada.value.trim()=="") return "";
				coordenadas = coordenada.value;
				pos = coordenada.value.split(/[°'"]/);
				if (pos.length==4) {	
					coordenadas= ConvertDMSToDD(Number(pos[0].trim()), Number(pos[1].trim()), Number(pos[2].trim()), pos[3].trim());
				} 
				return coordenadas;
			}			
        </script>
		<script type="text/javascript" src="Javascript/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="Javascript/jquery-ui-1.8.23.custom.min.js"></script>
		
		<script type="text/javascript" src="http://svn.osgeo.org/metacrs/proj4js/trunk/lib/proj4js-compressed.js"></script>
		<script type="text/javascript" src="http://spatialreference.org/ref/epsg/31467/proj4js/"></script>		
		
		
		<script type="text/javascript" src="Javascript/jquery.tinyscrollbar.min.js"></script>
		<script type="text/javascript" src="Javascript/libreriasAjax2/ajaxfileupload.js"></script>
        <script src="Javascript/jquery.placeholder.min.js"></script>        	
		<link href="Javascript/libreriasAjax2/ajaxfileupload.css" type="text/css" rel="stylesheet">		
        <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
		<script type="text/javascript">
			var winP=null, resultados=null;
			var map, drawControls, polygonFeature, vectorLayer, pointLayer, lineLayer, polygonLayer, boxLayer;
			var openSearch = 0, openProspect = 0;

			var measureControls; // 20160309
			var CONTAR_POLY = 0;
			var GLOBAL_POLY;
			var LIMIT_INFERIOR = <?=$_SESSION['rango_inferior']?>; 
			var LIMIT_SUPERIOR = <?=$_SESSION['rango_superior']?>;
			
			OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
			OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;	


			// dar color al layer de resultados
			var styleMap = new OpenLayers.StyleMap(  {'strokeWidth': 5,  'strokeColor': '#ff0000'});			

									
			// Definición de los sistemas de proyección:
			var projection = new OpenLayers.Projection("EPSG:900913");
			var displayProjection = new OpenLayers.Projection("EPSG:4326");		

			vectorLayer = new OpenLayers.Layer.Vector("Vector Layer",
						{
							styleMap: styleMap,
							projection: projection,
							displayProjection: displayProjection
						});			
			
            OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
                defaultHandlerOptions: {
                    'single': true,
                    'double': false,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': false
                },

                initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    ); 
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click': this.trigger
                        }, this.handlerOptions
                    );
                }, 

                trigger: function(e) {	
					var lonlat = map.getLonLatFromPixel(e.xy);
					lonlat = lonlat.transform(
								projection,
								displayProjection
							  );					
					coordenadas = "POINT(" + lonlat.lon + " " + lonlat.lat + ")";
				
		           	$.post('?fnd=identify_map', { coordenadasRAC: coordenadas}, function(resp) {
							$("#loadingImage").show();
		                	if(resp!="") {
								if(resultados != null) resultados.close();
								resultados = window.open("", "Ventana", "width=700 height=200 scrollbars=yes");
								resultados.document.title = ":: SIGMIN - Identify";								
								resultados.document.write(resp);								 		
								resultados.focus();							
								$("#loadingImage").hide();
							} else
			                	alert("No hay retorno de informaci&oacute;n");
						});									
                }
            });	
			
			function init() {
				$.post('viewServicesSIGMINFull.php', { loadService : true}, function(resp) { if(resp!="") eval(resp); else alert("falla al cargar los servicios geográficos");});				
            }
			
			function toggleControl(element) {
				clearFields();	
				document.calculo_area.infoAL.value = "";
				$("#infoAL").css("background-color","#FFF");
				measureControls["polygon"].deactivate();
				drawControls["polygon"].deactivate();
				
				if(element.id=='poligono')
				{
					document.getElementById("polygonToggle").click();
					document.tools.polygonToggle.click();
				}
				if(element.id=='area')
				{
					document.getElementById("pointToggle").click();
					document.tools.point.click();
				}
                for(key in drawControls) {
                    var control = drawControls[key];
                    if(element.value == key && element.checked) {   
						if(typeof(measureControls[key]) != "undefined")
							measureControls[key].activate();
					
						control.activate();		
                    } else {
						if(typeof(measureControls[key]) != "undefined") 
							measureControls[key].deactivate();			
						
                        control.deactivate();
                    }
                }	
            }
			
			function allowPan(element) {
                var stop = !element.checked;
                for(var key in drawControls) {
                    drawControls[key].handler.stopDown = stop;
                    drawControls[key].handler.stopUp = stop;
                }
            } 
			function cambiarExpediente(campoPlaca, tipoExp) {
            	$.post('viewValidaExpediente.php', { selExpediente: campoPlaca, tipoExpediente: tipoExp}, function(resp) {
                	if(resp!="")
        					eval(resp);
                    	else
		                	alert("No hay retorno de informaci&oacute;n");
				});
            }       
 			function showMultiExpedientes(queryPlacas) {
				vectorLayer.removeAllFeatures();
            	$.post('viewShowMultiExpediente.php', { selExpediente: queryPlacas }, function(resp) {
                	if(resp!="")
        					eval(resp);
                    	else
		                	alert("No hay retorno de informaci&oacute;n");
				});
            } 
            function clearFields() {
				lineLayer.removeAllFeatures();
				pointLayer.removeAllFeatures();
				lineLayer.removeAllFeatures();
				polygonLayer.removeAllFeatures();
				boxLayer.removeAllFeatures();
				vectorLayer.removeAllFeatures();	
			}
			function validarBusqueda() { 
				if(document.forms["searchWords"].txtBusqueda.value=="")  
					return 0;
				
				$("#loadingImage").show();
				$.post('viewServicesSIGMINFullResultados.php', { txtBuscar : document.forms["searchWords"].txtBusqueda.value}, function(resp) {
						if(resp!="")  {	
							if(resultados != null) resultados.close();
							resultados = window.open("", "Ventana", "width=700 height=450 scrollbars=yes");
    						resultados.document.write(resp);
							resultados.document.title = ":: SIGMIN - Results"; 		
							resultados.focus();

							$("#loadingImage").hide();							
						} else
							alert("No hay retorno de información");
					});				
			}
			 $(function(){
				$("#txtBusqueda").autocomplete({
				   source: "viewValidaQuery.php"
				});		
			});
			function cambiarProspecto(campoPlaca) { 
				$.post('viewValidaPlaca.php', { selProspecto: campoPlaca}, function(resp) {
						if(resp!="") 
							eval(resp);
						else
							alert("No hay retorno de informacion");
					});				
			}

			function Busc_Open(){
				openProspect = 0;				
				visualizar = 'NO';	
				if(openSearch%2==0) visualizar = 'SI'; 
				openSearch ++;
				
				if(visualizar=="SI") {
					$('#prospect').css('display','none');
					$('#prospect').animate({
					bottom: "-230px",
					left: "550px"
					}, 500 ); 					
					
					$('#buscar').css('display','block');			
					$('#buscar').animate({
					bottom: "350px",
					left: "170px"
					}, 500 );  
				} else {
					$('#buscar').css('display','none');
					$('#buscar').animate({
					bottom: "20px",
					left: "520px"
					}, 500 );										
				}					
			}

			function Pros_Open() {
				openSearch = 0;	
				visualizar = 'NO';	
				if(openProspect%2==0) visualizar = 'SI'; 
				openProspect ++; 

				if(visualizar=="SI") {
					$('#buscar').css('display','none');
					$('#buscar').animate({
					bottom: "20px",
					left: "520px"
					}, 500 ); 
				
					$('#prospect').css('display','block');			
					$('#prospect').animate({
					bottom: "100px",
					left: "170px"
					}, 500 ); 
				} else {
					$('#prospect').css('display','none');
					$('#prospect').animate({
					bottom: "-230px",
					left: "550px"
					}, 500 );					
				}	
			}
			
		</script>
	<script type="text/javascript" src="Javascript/fisheye-iutil.min.js"></script>
    <script type="text/javascript">
		$(function () { 
		$('#menu').Fisheye(
			{
				maxWidth: 40,
				items: 'a',
				itemsText: 'span',
				container: '.dock-container',
				itemWidth: 81,
				proximity: 90,
				alignment : 'left',
				valign: 'bottom',
				halign : 'center'
				}
			);
		});
		
		
		function controlEnter(e) {
			if (e.keyCode == 13) {
				validarBusqueda();
				return false;
			}		
		}
		
		$(document).ready(function() {
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

    <!--[if IE]>
            <link rel="stylesheet" href="Javascript/ie.css" media="screen" />
            <script type="text/javascript" src="Javascript/ie.js"></script>
        <![endif]-->
    <!--[if (gte IE 6)&(lte IE 8)]>
            <script type="text/javascript" src="Javascript/selectivizr.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
            <script src="Javascript/html5shiv.js"></script>
        <![endif]-->
</head>	
	<body onLoad="init()">	
	<?php include_once("analyticstracking.php") ?>
	
		<div id="prospect" >
        	<p class="fzg">Generar Area Libre</p>
			<div class="prospect_tools">
				<form method="post" target="pop" action="?fnd=prospectos" name="free">
					<input type="hidden" name="infoAL" class="selProspecto" value="" readonly>				
					<input type="hidden" name="coordenadasPry" value="" id="coordenadasPry"/>
					<input type="hidden" name="tipoOperaPry" value="crear prospecto" id="tipoOperaPry"/>
               		<input type="button" name="type" id="poligono" title="Dibujar Poligono" value="polygon" onClick="toggleControl(this);" class="drawpoly"/>
					<a href="javascript:" title="Coordenadas" class="cordi" onClick="mostrardiv('freeGeneratorArea_coordinates')">Coordenadas</a>
					<a href="javascript:" title="Crear Prospecto" class="crea_prospect" onClick="document.free.tipoOperaPry.value='crear prospecto'; if(winP != null) winP.close(); winP=window.open('', 'pop', 'width=800,height=600, resizable=yes, scrollbars=yes');	winP.document.title = ':: SIGMIN - Resultados'; winP.focus();  document.forms['free'].submit(); return false;">Crear Prospecto</a>
					<a href="javascript:" title="Superponer" class="over_ana" onClick="if(confirm('Desea consumir 5 cr\u00E9ditos por reporte de \u00E1rea libre?')){document.free.tipoOperaPry.value='superponer'; if(winP != null) winP.close(); winP=window.open('', 'pop', 'width=800,height=600, resizable=yes, scrollbars=yes');	winP.document.title = ':: SIGMIN - Resultados'; winP.focus();  document.forms['free'].submit(); return false; }">Superponer</a>				
            </form>
			</div>

		
			<form name="frmCoordinates" action="?fnd=simular_coordenadas" method="post" enctype="multipart/form-data">
			<div id="freeGeneratorArea_coordinates" class="loadFiles" style="display: none;">	
				<div class="titleLoadFiles">    
				:: Cargue de Coordenadas ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="cerrar('freeGeneratorArea_coordinates')" title="Cerrar ventana" style="color: #ffffff; text-decoration: none">[X]</a></center></div>
				<div>
					<div>
						<select id="selGeoSystem" name="selGeoSystem" class="selLoadFile">
							<option value="0">Seleccione el Sistema de Coordenadas
							<option value="WGS84">WGS84 Decimal
							<option value="BOGOTA">Colombia Bogota Zone
							<option value="ESTE-CENTRAL">Colombia E Central Zone
							<option value="ESTE-ESTE">Colombia East Zone
							<option value="OESTE">Colombia West Zone
						</select>
												
					</div>												
					<div class="formatoCoords">
						<b><i>Consideraciones:</i></b>
						<hr size="0" />
						<ul>
							<li>Formato por l&iacute;nea de Coordenada: <i><b>Norte : Este ; </b></i>
							<li>Extensi&oacute;n del archivo: <i><b>TextFile (.txt)</b></i>				
						</ul>	
						<hr size="0" />			
					</div>
						
						<img id="loading" src="Imagenes/loading.gif" style="display:none;">
						<input type="file" name="fileToUpload" id="fileToUpload" size="45"  class="selLoadFile">
						<hr size="0">
						<center>					
							<input type="button" class="botonPry" id="buttonUpload" onclick="return ajaxFileUpload();" value="Simular Poligono">
						</center>	
				</div>
			</div >
			</form>
									
		
			<p class="fzg">Alerta de Liberaci&oacute;n de &Aacute;rea</p>
			<div class="prospect_tools">
				<form method="post" target="pop" action="?fnd=generar_alerta" name="alarm">	
					<input type="hidden" name="infoAL" class="selProspecto" value="" readonly>				
					<input type="hidden" name="coordenadasPry" value="" id="coordenadasPry"/>
					<input type="button" name="type" id="poligono" title="Dibujar Poligono" value="polygon" onClick="toggleControl(this);" class="drawpoly"/>
					<a href="javascript:" title="Coordenadas" class="cordi" onClick="mostrardiv('freeGeneratorArea_coordinates')">Coordenadas</a>				
					<a href="javascript:" title="Generar Alerta" class="over_ana" onClick="if(confirm('Desea consumir 5 cr\u00E9ditos por generaci\u00F3n de alerta de \u00E1rea libre?')){ if(winP != null) winP.close(); winP=window.open('', 'pop', 'width=600,height=500, resizable=yes, scrollbars=yes');	winP.document.title = ':: SIGMIN - Resultados'; winP.focus(); document.forms['alarm'].submit();return false; }">Generar Prospecto</a>
					
				</form>
			</div>
			
			<form name="frmCoordinatesPoint" action="?fnd=simular_coordenadas" method="post" enctype="multipart/form-data">
			<div id="point_coordinates" class="loadFiles" style="display: none;">	
				<div class="titleLoadFiles">    
				:: Cargue de Coordenadas ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="cerrar('point_coordinates')" title="Cerrar ventana" style="color: #ffffff; text-decoration: none">[X]</a></center></div>
				<div>
					<div>
						<select id="selGeoSystem" name="selGeoSystem" class="selLoadFile">
							<option value="0">Seleccione el Sistema de Coordenadas
							<option value="WGS84">WGS84 (GMS o Decimal)
							<option value="BOGOTA">Colombia Bogota Zone
							<option value="ESTE-CENTRAL">Colombia E Central Zone
							<option value="ESTE-ESTE">Colombia East Zone
							<option value="OESTE">Colombia West Zone
						</select>
												
					</div>												
					<div class="formatoCoords">
						<b><i>Consideraciones:</i></b>
						<hr size="0" />
						Ingreso de Coordenadas (Considere el Sistema de Origen):<br/>	
						<input type="" name="coordX" class="txtRadCoord" placeholder="Este/Longitud" onchange="this.value=procesarGMS(this)"/> &nbsp;&nbsp;,&nbsp;&nbsp;
						<input type="" name="coordY" class="txtRadCoord" placeholder="Norte/Latitud" onchange="this.value=procesarGMS(this)"/><br/>
						<div>&nbsp; &nbsp;</div>
					</div>		
					<div>&nbsp; &nbsp;</div>
					<hr size="0">
					<center>					
						<input type="button" class="botonPry" id="buttonUpload" onclick="pointAddedCoords()" value="Ubicar Coordenada">
					</center>	
				</div>
			</div >
			</form>			
		
			<p class="fzg">An&aacute;lisis Perimetral</p>
			<div class="prospect_tools">
				<form method="post" target="pop2" action="?fnd=analisis_perimetral&credits=1" name="neighbor">
					<input type="hidden" name="coordenadasRAC" value="" id="coordenadasRAC"/>
					<input type="button" name="type" id="area" value="point" onClick="toggleControl(this);" class="drawpoint" title="Ubicar Punto"/>
					<a href="javascript:" title="Coordenadas" class="cordi" onClick="mostrardiv('point_coordinates')">Coordenadas</a>
					<p class="txt">Radio(m)</p><input class="txtRad" type="number" name="txtRadio"  value="2000" min="0" max="15000">
						<a href="javascript:" onClick="if(confirm('Desea consumir 3 cr\u00E9ditos por generaci\u00F3n de reporte perimetral?')){ if(winP != null) winP.close(); winP=window.open('', 'pop2', 'width=800,height=600, resizable=yes, scrollbars=yes');	winP.document.title = ':: SIGMIN - Resultados'; document.forms['neighbor'].submit();return false; }" class="over_ana2" title="Análisis de Radio">Análisis de Radio</a>
				</form>	
			</div>
			
			<form method="post" name="calculo_area">				
			<div style="padding: 10; padding-left: 20px; margin-top:10px; height:20px; background-color: #F5F8DE">
				C&aacute;lculo de &Aacute;rea: <input type="text" name="infoAL" id="infoAL" value="0" readonly style="border: 1px solid; width: 130px; padding-left:5px"> 
			</div>	
			</form>	
		</div>
	
		<div id="buscar">
			<form name="searchWords">			
			<input id="txtBusqueda" size=50 class="box" placeholder="Palabra Clave ...">
			<a href="javascript:" onClick="validarBusqueda();" class="sboton" id="sboton">Buscar</a>
			</form>	
		</div>	
		
        <a href="?pagina=map" class="logo" id="logo"></a>
        <div style="position:absolute; bottom:15px; width:100%; z-index:99999;">
        <div id="menu">
				<div class="dock-container">
					<a class="dock-item" href="javascript:" onClick="Busc_Open()"><span class="blue">Search</span><img src="Imagenes/search_icon.png" alt="Search" title="Búsqueda Multicriterio en el Catastro Minero" /></a> 

<?php
//	if(@$_SESSION["usuario_sgm"]=="jmoreno" || @$_SESSION["usuario_sgm"]=="jecardenas" || @$_SESSION["usuario_sgm"]=="jvelasquez"  || @$_SESSION["usuario_sgm"]=="ahmarino@hotmail.com") {		
		$funcionProspect = "Pros_Open()";
?>					
					<a class="dock-item" href="javascript:" onClick="<?php echo $funcionProspect ?>"><span class="red">Prospect</span><img src="Imagenes/prospect_icon.png" alt="Prospect" title="Generación de Nuevas Oportunidades Mineras" /></a>
<?php
//	}
?>
					<!-- <a class="dock-item" href="javascript:" onClick="Clave_Open()"><span class="purple">Account</span><img src="Imagenes/settings_icon.png" alt="account" /></a>  -->
					<a class="dock-item" href="javascript:" onClick="document.location.href='?pagina=account'"><span class="purple">Account</span><img src="Imagenes/settings_icon.png" alt="account" /></a> 
				
				</div>
		</div>
        </div>
        <div id="map" style='width: 100%; height: 100%; border:0;'></div>
		<div id="capas" onClick="mover_capas();" title="inicio">
			<div id="capas2"></div>
		</div>
        <div id="tools" onClick="mover_tools();" title="inicio" style="display:none">
           		<input type="radio" name="type" value="none" id="noneToggle" onClick="toggleControl(this);" checked="checked" style="display:none;" />
                <input type="radio" name="type" value="point" id="pointToggle" onClick="toggleControl(this);" class="point"/>
                <label for="pointToggle" class="point2" title="Draw Point">draw point</label>
                <input type="radio" name="type" value="line" id="lineToggle" onClick="toggleControl(this);" class="line" />
                <label for="lineToggle" class="line2" title="Draw Line">draw line</label>
                <input type="radio" name="type" value="polygon" id="polygonToggle" onClick="toggleControl(this);" class="polygon" />
                <label for="polygonToggle" class="polygon2" title="Draw Polygon">draw polygon</label>
                <input type="radio" name="type" value="box" id="boxToggle" onClick="toggleControl(this);" class="box3"/>
                <label for="boxToggle" class="box2" title="Draw Box">draw box</label>
              	<input type="checkbox" name="allow-pan" value="allow-pan" id="allowPanCheckbox" checked=false onClick="allowPan(this);" style="display:none;" />
                <a href="javascript:" onClick="clearFields();" class="clean" title="Clean All">Limpiar</a>
        </div>
        
<script type="text/javascript">

$(document).ready(function() 
{
   $('#buscar').draggable();  
   $('#prospect').draggable(); 
   $('#ly_acount').draggable(); 
});

</script>
		<?php
			if(!empty($msgError) && $msgError!="")
				echo $msgError;
		?>	
<script>
    $('input[placeholder], textarea[placeholder]').placeholder();
</script>	
	<img src="Javascript/images/loading_sgm.gif" width="140" height="140" id="loadingImage" style="display:none; top:50%; left:50%; z-index:2000; position:fixed !important; opacity: 0.65;" />	
	</body>
</html>
