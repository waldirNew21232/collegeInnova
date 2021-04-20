<?php

	session_start();
	
	if(isset($_SESSION['colegio']['user'])){
	
		session_destroy();
		header('Location:index.php');
	
	}else{
	
		echo "<div style='font-weight:bold;'>ERROR</div>";
	
	}

?>