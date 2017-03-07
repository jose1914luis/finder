<?php
	// remove all session variables
	session_unset(); 
	// destroy the session 
	session_destroy(); 
	$_SESSION = array();
	
?>	
	<script>document.location.href="<?=$GLOBALS ["sitio"]?>";</script>



