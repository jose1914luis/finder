#!/bin/bash
echo " ";
echo "----------------------------------------------------"
echo "Placa de Prospecto: $1 con Session: $2"
echo "Generando shape de Prospecto ...."
pgsql2shp -f "DwnShapes/ProspectoBog_$1_$2" -u cmqpru -P 2012zygMin cmqpru "select placa, fecha_creacion, sistema_origen, area::float/10000 as area_hectareas, perimetro, the_geom from prospectos_bog_sgm where placa='$1' limit 1"

echo "Comprimiendo archivo para descarga ...."
zip DwnShapes/shpProspectoBog_$1_$2 DwnShapes/ProspectoBog_$1_$2*
rm DwnShapes/ProspectoBog_$1_$2* 

echo "----------------------------------------------------"
echo "Fin de la operacion"
