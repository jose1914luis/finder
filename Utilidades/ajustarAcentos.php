<?php

    /*
     *  Funci�n para ajustar la presentaci�n de los mensajes
     *  en autocompletar y menus desplegables
     */
    function AjustarAcentos($valorCampo) {
	/*
		$valorCampo = str_replace("&Aacute;","�",$valorCampo);
        $valorCampo = str_replace("&Eacute;","�",$valorCampo);
        $valorCampo = str_replace("&Iacute;","�",$valorCampo);
        $valorCampo = str_replace("&Oacute;","�",$valorCampo);
        $valorCampo = str_replace("&Uacute;","�",$valorCampo);

        $valorCampo = str_replace("&aacute;","�",$valorCampo);
        $valorCampo = str_replace("&eacute;","�",$valorCampo);
        $valorCampo = str_replace("&iacute;","�",$valorCampo);
        $valorCampo = str_replace("&oacute;","�",$valorCampo);
        $valorCampo = str_replace("&uacute;","�",$valorCampo);
	*/
        $valorCampo = str_replace("&Ntilde;",utf8_encode("�"),$valorCampo);
        $valorCampo = str_replace("&ntilde;",utf8_encode("�"),$valorCampo);   
	
        $valorCampo = str_replace("?",utf8_encode("�"), $valorCampo);         

        return $valorCampo;         
    }
    


?>

