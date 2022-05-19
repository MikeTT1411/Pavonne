<?php
    $dsn = 'mysql:host=localhost;dbname=pavonnebd';
	$user = 'root';
	$pass = '';
	$option = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	);
	try
	{
		$con = new PDO($dsn,$user,$pass,$option);
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex)
	{
		echo "Error al conectar la base de datos ! ".$ex->getMessage();
		die();
	}
	
	$connect = mysqli_connect("localhost", "root", "", "pavonnebd");
	$connect -> set_charset("utf8");
?>