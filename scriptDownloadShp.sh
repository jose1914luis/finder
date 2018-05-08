#!/bin/bash
echo " "
echo "----------------------------------------------------"
echo "ID de la Session: $1"
echo "Generando shape de Solicitudes ...."
pgsql2shp -f "DwnShapes/SolSIGMIN_Bog_$1" -u cmqpru -P 2012zygMin cmqpru "select id_session, placa, modalidad, estado_juridico, grupo_trabajo, formulario, fecha_radicacion, fecha_terminacion, fecha_otorgamiento, fecha_creacion, area_solicitada_ha, area_definitiva_ha, observacion, justificacion_extemporanea, substring(municipios,1,255) as municipios, substring(solicitantes,1,255) as solicitantes, substring(minerales,1,255) as minerales, plancha_igac, sistema_origen, descripcion_pa, the_geom from dwn_solicitudes_bog s where id_session = '$1'"

echo "Generando shape de titulos ...."
pgsql2shp -f "DwnShapes/TitSIGMIN_Bog_$1" -u cmqpru -P 2012zygMin cmqpru "select id_session, placa, codigo_rmn, codigo_anterior, modalidad, estado_juridico, grupo_trabajo, fecha_inscripcion, fecha_contrato, fecha_terminacion, fecha_creacion, area_otorgada_ha, area_definitiva_ha, substring(municipios,1,255) as municipios, substring(titulares,1,255) as titulares, substring(minerales,1,255) as minerales, plancha_igac, sistema_origen, descripcion_pa, the_geom from dwn_titulos_bog where id_session='$1'"


echo "Generando shape de prospectos ...."
pgsql2shp -f "DwnShapes/ProspSIGMIN_Bog_$1" -u cmqpru -P 2012zygMin cmqpru "select ps.placa, ps.sistema_origen, ps.area_ini/10000 as area_inicial_has, ps.area_fin/10000 as area_final_has, ps.the_geom from prospectos_superposiciones_bog_sgm  ps inner join (select trim(unnest(string_to_array('$2',','))) as placa_usr) t2  on ps.placa=t2.placa_usr"


echo "Comprimiendo archivos para descarga ...."
zip DwnShapes/geoSIGMIN_Bog_$1 DwnShapes/SolSIGMIN_Bog_$1* DwnShapes/TitSIGMIN_Bog_$1* DwnShapes/ProspSIGMIN_Bog_$1*
rm DwnShapes/SolSIGMIN_Bog_$1* DwnShapes/TitSIGMIN_Bog_$1* DwnShapes/ProspSIGMIN_Bog_$1*


echo "----------------------------------------------------"
echo "Fin de la operacion"
