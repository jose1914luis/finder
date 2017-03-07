<?php

    /*
     *  Función para ajustar la presentación de los mensajes
     *  en autocompletar y menus desplegables
     */
    function AjustarAcentos($valorCampo) {
	/*
		$valorCampo = str_replace("&Aacute;","Á",$valorCampo);
        $valorCampo = str_replace("&Eacute;","É",$valorCampo);
        $valorCampo = str_replace("&Iacute;","Í",$valorCampo);
        $valorCampo = str_replace("&Oacute;","Ó",$valorCampo);
        $valorCampo = str_replace("&Uacute;","Ú",$valorCampo);

        $valorCampo = str_replace("&aacute;","á",$valorCampo);
        $valorCampo = str_replace("&eacute;","é",$valorCampo);
        $valorCampo = str_replace("&iacute;","í",$valorCampo);
        $valorCampo = str_replace("&oacute;","ó",$valorCampo);
        $valorCampo = str_replace("&uacute;","ú",$valorCampo);
	*/
        $valorCampo = str_replace("&Ntilde;",utf8_encode("Ñ"),$valorCampo);
        $valorCampo = str_replace("&ntilde;",utf8_encode("ñ"),$valorCampo);   
	
        $valorCampo = str_replace("?",utf8_encode("Ñ"), $valorCampo);         

        return $valorCampo;         
    }
    


?>

