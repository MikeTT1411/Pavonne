<?php
	session_start();

	// Si el usuario ya hizo login
	if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
	{
		header('Location: index.php');
		exit();
	}

	$pageTitle = 'Log in';
	include 'connect.php';
	include 'Includes/functions/functions.php';


?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pavonne Admin Login</title>
		<!-- FONTS FILE -->
		<link href="Design/fonts/css/all.min.css" rel="stylesheet" type="text/css">

		<!-- Nunito FONT FAMILY FILE -->
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

		<!-- CSS FILES -->
		<link href="Design/css/sb-admin-2.css" rel="stylesheet">
		<link href="Design/css/main.css" rel="stylesheet">
	</head>
	<body class="bg-dark">
		<div class="login">
			<form class="login-container validate-form" name="login-form" method="POST" action="login.php" onsubmit="return validateLogInForm()">
				<div class="d-flex justify-content-center">
					<img class="img-fluid" src="../img/logo/logo-2.png" alt="">
				</div>

				<?php

					if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin-button']))
					{
						$username = test_input($_POST['username']);
						$password = test_input($_POST['password']);
						$hashedPass = sha1($password);

						//Checha si el usuario existe en la base de datos

						$stmt = $con->prepare("Select adminID, usuario,password from admin where usuario = ? and password = ?");
						$stmt->execute(array($username,$hashedPass));
						$row = $stmt->fetch();
						$count = $stmt->rowCount();

						if($count > 0)
						{

							$_SESSION['username_barbershop_Xw211qAAsq4'] = $username;
							$_SESSION['password_barbershop_Xw211qAAsq4'] = $password;
							$_SESSION['admin_id_barbershop_Xw211qAAsq4'] = $row['adminID'];
							header('Location: index.php');
							die();
						}
						else
						{
							?>

							<div class="alert alert-danger">
								<button data-dismiss="alert" class="close close-sm" type="button">
									<span aria-hidden="true">×</span>
								</button>
								<div class="messages">
									<div>El Usuario o Contraseña son incorrectos</div>
								</div>
							</div>

							<?php
						}
					}

				?>

				<!-- Usuario -->

				<div class="form-input">
					<span class="txt1">Usuario</span>
					<input type="text" name="username" class = "form-control" oninput = "getElementById('required_username').style.display = 'none'" autocomplete="off">
					<span class="invalid-feedback" id="required_username">El usuario es requerido</span>
				</div>
				
				<!-- contraseña -->

				<div class="form-input">
					<span class="txt1">Contraseña</span>
					<input type="password" name="password" class="form-control" oninput = "getElementById('required_password').style.display = 'none'" autocomplete="new-password">
					<span class="invalid-feedback" id="required_password">La contraseña es requerida</span>
				</div>
				
				<!-- botton ingresar -->

				<p>
					<button type="submit" name="signin-button" >Ingresar</button>
				</p>

			</form>
		</div>
		
		<!-- Footer -->
		<footer class="sticky-footer bg-white">
			<div class="container my-auto">
		  		<div class="copyright text-center my-auto">
					<span>Copyright &copy; Pavonne Barbiere</span>
		  		</div>
			</div>
	  	</footer>

		<!-- SCRIPTS -->
		<script src="Design/js/jquery.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="Design/js/bootstrap.bundle.min.js"></script>
		<script src="Design/js/sb-admin-2.min.js"></script>
		<script src="Design/js/main.js"></script>
	</body>
</html>
