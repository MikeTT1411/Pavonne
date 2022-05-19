<?php
	//Iniciar sesion
	session_start();
	//Unsetear las variables de sesion
	session_unset();
	//Destruir sesion
	session_destroy();
	header('Location: login.php');
	exit();
?>