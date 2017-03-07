<?php
/*
	Clase encargada de la administración y gestión de los expedientes en el SIGMIN
*/

	class ExpedientesSGM {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase ExpedientesSGM.\n";
				return 0;
			}
		}
		
		function selectExpedienteByPlaca($placa) {
			$queryStr =  "
				select placa, tipo_expediente from (
					select s.placa, 'SOLICITUD' as tipo_expediente from solicitudes s
					left join titulos t on s.placa=t.placa
					where t.placa is null
					union
					select s.placa, 'TITULO' as tipo_expediente from titulos s
				) ex where ex.placa=$1	limit 1		
			";	
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al consultar expediente por placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}			
		
		function listaAlertasExpedientesArchivados($minerales, $personas, $municipios, $modalidad) {
			$queryStr =  "
				select * from (
					select	distinct
						al.placa,
						al.id_tipo_alerta,
						to_char(al.fecha_notificacion,'DD/MM/YYYY') as fecha_notificacion,
						to_char(al.fecha_vencimiento - '1 second'::interval,'DD/MM/YYYY HH24:MI') as fecha_vencimiento,
						case
							when t.id is not null then 'TITULO'
							when s.id is not null then 'SOLICITUD'
							else null
						end as tipo_expediente,					
						case
							when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MUNICIPIOS')
							when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MUNICIPIOS')
							else null
						end as municipios,
						case
							when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_PERSONAS')
							when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_PERSONAS')
							else null
						end as personas,
						case
							when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MINERALES')
							when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MINERALES')
							else null
						end as minerales,
						case
							when t.id is not null then t.modalidad
							when s.id is not null then s.modalidad
							else null
						end as modalidad		
					from alertas al 
						left join (select tt.* from titulos tt inner join titulos_cg tg on (tt.id=tg.id_titulo))t on (al.placa=t.placa)
						left join (select ss.* from solicitudes ss inner join solicitudes_cg sg on (sg.id_solicitud=ss.id)) s on (al.placa=s.placa)		 
					where  al.fecha_vencimiento::date - 1 > now()::date
						and (s.area_definitiva > 0 or s.area_definitiva is null)
				) ex
					where minerales like $1 and personas like $2 and municipios like $3 and modalidad like $4
				order by 4	
			";	
			$result = pg_query_params($this->conn, $queryStr, array("%$minerales%", "%$personas%", "%$municipios%", "%$modalidad%"));
			
			if (!$result) {
			  echo "Error al consultar alerta de archivadas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}			
	}	
?>

