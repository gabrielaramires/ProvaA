<?php 

	session_start();

	if (isset($_SESSION["id_usuario"]) == false)
		header("location: index.php?erro=2");

?>