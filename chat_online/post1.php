<?php
	session_start();			
	$text 	= $_POST['text'];	
	$id 	= $_POST['id'];	
	if(isset($_SESSION["lista_archivos"][$id]["nombre_archivo"])) {		
		$fp 	= fopen($_SESSION["lista_archivos"][$id]["nombre_archivo"], 'a');		
		fwrite($fp, "<div class='adminbox'>(".date("d-m-Y g:i A").") <b>SIGMIN</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");		
		fclose($fp);	
	} else
		echo "Error, no encuentra el archivo ...";
?>