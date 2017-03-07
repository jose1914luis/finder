#!/bin/bash
echo " ";
echo "----------------------------------------------------"
echo "Placa de Prospecto: $1 con Session: $2"
echo "Generando shape de Area Libre ...."
pgsql2shp -f "DwnShapes/AreaLibre_Bog_$2" -u cmqpru -P 2012zygMin cmqpru "select ps.placa, ps.sistema_origen, ps.area_ini/10000 as area_inicial_has, ps.area_fin/10000 as area_final_has, ps.the_geom from prospectos_superposiciones_bog_sgm  ps where placa='$1' limit 1"

echo "Comprimiendo archivo para descarga ...."
zip DwnShapes/shpAreaLibre_Bog_$2 DwnShapes/AreaLibre_Bog_$2*
rm DwnShapes/AreaLibre_Bog_$2* 

echo "----------------------------------------------------"
echo "Fin de la operacion"
