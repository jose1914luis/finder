<?php
/**
 * EBILL
 */


class FacturaEbill
{
	


	public function consumir($servicio, $parametros) {
		
				
			switch ( $servicio ) {
				//Mensaje de Alta de Actuacion
				case "CargarFacturasToeBillGeneric":

					// inicio de consumo de servicio
					$cliente = new SoapClient('http://fym.cloudapp.net/ServiciosFyM/eBill/Integracion.svc?wsdl', array('exceptions' => 0));
				
					
					try {
						
						$resultado = $cliente->CargarFacturasToeBillGeneric($parametros);
						
					} catch (SoapFault $e) {
						//pendiente fault
						$resultado = "Mensaje XML: \n".print_r($cliente->__getLastRequest(), TRUE) . "\nRespuesta: \n" . print_r($e, TRUE);
						return false;
					}
	
	
				break;
				
		
			}
		
		return $resultado;
	}
}
?>