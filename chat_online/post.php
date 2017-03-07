<?php
	session_start();	
	if(isset($_SESSION['name'])) {		
		$text = $_POST['text'];		
		$fp = fopen($_SESSION['archivo'], 'a');		
		fwrite($fp, "<div class='msgln'>(".date("d-m-Y g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");		
		fclose($fp);	
	}
?>