<?php

    include "connect.php";
    include "includes/functions/functions.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pavonne Barbiere</title>
    <link rel="shortcut icon" href="img/logo/logo.png" type = "image/x-icon">

    <!-- Site Icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&family=Oranienbaum&family=Rufina&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Citas Stylesheet -->
	  <link rel="stylesheet" href="css/citas-style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <!--header-->
    <nav class="header navbar navbar-expand-lg navbar-dark" style="background-color: #202020;">
      <a class="navbar-brand d-lg-none" href="index.html"><img src="img/logo/logo.png"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbarToggler7"
          aria-controls="myNavbarToggler7" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="myNavbarToggler7">
          <ul class="navbar-nav mx-auto d-flex align-items-center">
              <li class="nav-item mx-3">
                <a href="index.html">Inicio</a>
              </li>
              <li class="nav-item mx-3">
                <a href="servicios.php">Servicios</a>
              </li>
              <li class="nav-item mx-3">
                <a href="productos.php">Productos</a>
              </li>
              <a class="d-none d-lg-block mx-5" href="#"><img src="img/logo/logo.png"></a>
              <li class="nav-item mx-3 ">
                <a href="https://www.facebook.com/barberiapavonnebarbiere" target="_blank"><ion-icon name="logo-facebook"></ion-icon></a>
              </li>
              <li class="nav-item mx-3">
                <a href="https://www.instagram.com/barberia.pavonne.mx/" target="_blank"><ion-icon name="logo-instagram"></ion-icon></a>
              </li>
              <li class="nav-item mx-3">
                  <a class="boton-1" href="agendar.php">Agendar cita</a>
              </li>
              
          </ul>
      </div>
    </nav>
    <!--header-->
    
    <!-- Container -->
    <div class="d-flex align-items-center bg-success p-5 bg-dark">
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-md-5 p-0">
                    <img class="img-fluid" src="img/agendar.jpg" alt="">
                </div>
                <div class="col-md-7 bg-1 scroll-div">
                <section class="booking_section">
	<div class="container">
        <?php
            date_default_timezone_set('America/Mexico_City');
            $appointment_date = date('Y-m-d');
            //echo $appointment_date;
        ?>

		<?php

            if(isset($_POST['submit-cita']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
            	// Selected SERVICES

                $servicios_seleccionados = $_POST['servicios_seleccionados'];

                // Selected EMPLOYEE

                $empleado_seleccionado = $_POST['empleado_seleccionado'];

                // Selected DATE+TIME

                $fecha_deseada_seleccionada = explode(' ', $_POST['fecha_deseada']);

                $fecha_seleccionada = $fecha_deseada_seleccionada[0];
                $hora_inicio = $fecha_seleccionada." ".$fecha_deseada_seleccionada[1];
                $hora_terminado = $fecha_seleccionada." ".$fecha_deseada_seleccionada[2];


                //Client Details

                $cliente_nombre = test_input($_POST['cliente_nombre']);
                $cliente_apellido = test_input($_POST['cliente_apellido']);
                $cliente_telefono = test_input($_POST['cliente_telefono']);
                $cliente_email = test_input($_POST['cliente_email']);

                $con->beginTransaction();

                try
                {
					// Check If the client's email already exist in our database
					$stmtCheckCliente = $con->prepare("SELECT * FROM clientes WHERE email = ?");
                    $stmtCheckCliente->execute(array($cliente_email));
					$cliente_resultado = $stmtCheckCliente->fetch();
					$cliente_count = $stmtCheckCliente->rowCount();

					if($cliente_count > 0)
					{
						$clienteID = $cliente_resultado["clienteID"];
					}
					else
					{
						$stmtgetCurrentClienteID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'pavonnebd' AND TABLE_NAME = 'clientes'");
            
						$stmtgetCurrentClienteID->execute();
						$clienteID = $stmtgetCurrentClienteID->fetch();

						$stmtCliente = $con->prepare("insert into clientes(nombre,apellido,telefono,email) 
									values(?,?,?,?)");
						$stmtCliente->execute(array($cliente_nombre,$cliente_apellido,$cliente_telefono,$cliente_email));
					}


                    

                    $stmtgetCurrentCitaID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'pavonnebd' AND TABLE_NAME = 'citas'");
            
                    $stmtgetCurrentCitaID->execute();
                    $cita_ID = $stmtgetCurrentCitaID->fetch();
                    
                    $stmt_appointment = $con->prepare("insert into citas(fecha, clienteID, empleadoID, hora_inicio, hora_terminado ) values(?, ?, ?, ?, ?)");
                    $stmt_appointment->execute(array(Date("Y-m-d H:i"),$clienteID[0],$empleado_seleccionado,$hora_inicio,$hora_terminado));

                    foreach($servicios_seleccionados as $servicio)
                    {
                        $stmt = $con->prepare("insert into serviciosagendados(citaID, servicioID) values(?, ?)");
                        $stmt->execute(array($cita_ID[0],$servicio));
                    }
                    
                    echo "<div class = 'alert alert-success'>";
                        echo "Bien! Tu cita ha sido agendada exitosamente.";
                    echo "</div>";

                    $con->commit();
                }
                catch(Exception $e)
                {
                    $con->rollBack();
                    echo "<div class = 'alert alert-danger'>"; 
                        echo $e->getMessage();
                    echo "</div>";
                }
            }

        ?>

		<!-- RESERVATION FORM -->

		<form method="post" id="cita_form" action="agendar.php">
		
			<!-- SELECT SERVICE -->

			<div class="select_servicios_div tab_reservacion" id="servicios_tab">

				<!-- ALERT MESSAGE -->

				<div class="alert alert-danger" role="alert" style="display: none">
					Por favor, selecciona al menos un servicio!
				</div>

				<div class="text_header">
					<span>
						1. Escoge tus servicios
					</span>
				</div>

				<!-- SERVICES TAB -->
				
				<div class="items_tab">
        			<?php
        				$stmt = $con->prepare("Select * from servicios");
                    	$stmt->execute();
                    	$rows = $stmt->fetchAll();

                    	foreach($rows as $row)
                    	{
                        	echo "<div class='itemListElement'>";
                            	echo "<div class = 'item_details'>";
                                	echo "<div>";
                                    	echo $row['nombre'];
                                	echo "</div>";
                                	echo "<div class = 'item_select_part'>";
                                		echo "<span class = 'duracion_field'>";
                                    		echo $row['duracion']." min";
                                    	echo "</span>";
                                    	echo "<div class = 'precio_field'>";
    										echo "<span style = 'font-weight: bold; color:#B29657;'>";
                                    			echo $row['precio']."$";
                                    		echo "</span>";
                                    	echo "</div>";
                                    ?>
                                    	<div class="select_item_bttn">
                                    		<div class="btn-group-toggle" data-toggle="buttons">
												<label class="servicio_label item_label btn btn-secondary">
													<input type="checkbox"  name="servicios_seleccionados[]" value="<?php echo $row['servicioID'] ?>" autocomplete="off">Añadir
												</label>
											</div>
                                    	</div>
                                    <?php
                                	echo "</div>";
                            	echo "</div>";
                        	echo "</div>";
                    	}
            		?>
    			</div>
			</div>

			<!-- SELECT EMPLOYEE -->

			<div class="select_empleado_div tab_reservacion" id="empleado_tab">

				<!-- ALERT MESSAGE -->

				<div class="alert alert-danger" role="alert" style="display: none">
					Por favor, selecciona a tu barbero!
				</div>

				<div class="text_header">
					<span>
						2. Selecciona a tu barbero
					</span>
				</div>

				<!-- EMPLOYEES TAB -->
				
				<div class="btn-group-toggle" data-toggle="buttons">
					<div class="items_tab">
        				<?php
        					$stmt = $con->prepare("Select * from empleados");
                    		$stmt->execute();
                    		$rows = $stmt->fetchAll();

                    		foreach($rows as $row)
                    		{
                        		echo "<div class='itemListElement'>";
                            		echo "<div class = 'item_details'>";
                                		echo "<div>";
                                    		echo $row['nombre']." ".$row['apellido'];
                                		echo "</div>";
                                		echo "<div class = 'item_select_part'>";
                                    ?>
                                    		<div class="select_item_bttn">
                                    			<label class="item_label btn btn-secondary active">
													<input type="radio" class="radio_employee_select" name="empleado_seleccionado" value="<?php echo $row['empleadoID'] ?>">Añadir
												</label>	
                                    		</div>
                                    <?php
                                		echo "</div>";
                            		echo "</div>";
                        		echo "</div>";
                    		}
            			?>
    				</div>
    			</div>
			</div>


			<!-- SELECT DATE TIME -->

			<div class="select_fecha_div tab_reservacion" id="calendario_tab">

				<!-- ALERT MESSAGE -->
				
		        <div class="alert alert-danger" role="alert" style="display: none">
		          Por favor, selecciona una hora!
		        </div>

				<div class="text_header">
					<span>
						3. Escoge el día y la hora
					</span>
				</div>
				
				<div class="calendario_tab" style="overflow-x: auto;overflow-y: visible;" id="calendario_tab_in">
					<div id="calendar_loading">
						<img src="Design/images/ajax_loader_gif.gif" style="display: block;margin-left: auto;margin-right: auto;">
					</div>
				</div>

			</div>


			<!-- CLIENT DETAILS -->

			<div class="cliente_details_div tab_reservacion" id="cliente_tab">

                <div class="text_header">
                    <span>
                        4. Detalles del cliente
                    </span>
                </div>

                <div>
                    <div class="form-group colum-row row">
                        <div class="col-sm-6">
                            <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control" placeholder="Nombre">
							<span class = "invalid-feedback">Este campo es requerido</span>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="cliente_apellido" id="cliente_apellido" class="form-control" placeholder="Apellido">
							<span class = "invalid-feedback">Este campo es requerido</span>
                        </div>
                        <div class="col-sm-6">
                            <input type="email" name="cliente_email" id="cliente_email" class="form-control" placeholder="Correo">
							<span class = "invalid-feedback">Correo inválido</span>
                        </div>
                          <div class="col-sm-6">
                            <input type="text"  name="cliente_telefono" id="cliente_telefono" class="form-control" placeholder="Número Telefónico">
                            <span class = "invalid-feedback">Número Telefónico inválido</span>
						</div>
            <div class="col-sm-6">
                            <br>
                            <div class="g-recaptcha" data-sitekey="6LcNvPofAAAAAEVd5TsElqvCgo4zUxLYxBWLqWLC"></div>
                        </div>
                        <div class="col-sm-6">
                            <br>
                            <p style="color: #B29657; font-size: 15px;">¡Procura llegar no después de 5 minutos de tu cita!</p>
                        </div>
                    </div>
        
                </div>
            </div>


			

			<!-- NEXT AND PREVIOUS BUTTONS -->

			<div style="overflow:auto;padding: 30px 0px;">
    			<div style="float:right;">
    				<input type="hidden" name="submit-cita">
      				<button type="button" id="prevBtn"  class="next_prev_buttons"  onclick="nextPrev(-1)">Anterior</button>
      				<button type="button" id="nextBtn" class="next_prev_buttons" onclick="nextPrev(1)">Siguiente</button>
    			</div>
  			</div>

  			<!-- Circles which indicates the steps of the form: -->

  			<div style="text-align:center;margin-top:40px;">
    			<span class="step"></span>
    			<span class="step"></span>
    			<span class="step"></span>
    			<span class="step"></span>
  			</div>

		</form>
	</div>
</section>
                </div>
            </div>
        </div>
    </div>

    <!-- Container -->

    <footer class="bg-1 text-white border-top">
      <!-- Grid container -->
      <div class="container p-4">
    
        <!-- Section: Social media -->
        <section class="mb-4">
          <!-- Facebook -->
    
          
        </section>
        <!-- Section: Social media -->
    
    
    
    
        <!-- Section: Links -->
        <section class="">
          <!--Grid row-->
          <div class="row">
            <!--Grid column-->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
              <img class="img-fluid" src="img/logo/logo-2.png" alt="">
            </div>
            <!--Grid column-->
    
            <!--Grid column-->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase text-accent2">Dirección</h5>
    
              <p class="lighter"><small>Río Amazonas 25
                <br> Del Valle, 66220
                <br> San Pedro Garza García, N.L.</small> </p>
            </div>
            <!--Grid column-->
    
            <!--Grid column-->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase text-accent2">Teléfono</h5>
              <p class="lighter"><small>2723 0310</p></small> 
              <h5 class="text-uppercase text-accent2">Horario</h5>
              <p class="lighter"><small>Lunes a Sábado
                <br> 11:00AM - 9:00PM</p></small>
            </div>
            <!--Grid column-->
    
          </div>
          <!--Grid row-->
        </section>
        <!-- Section: Links -->
    
      </div>
      <!-- Grid container -->
    
      <!-- Copyright -->
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        © 2022 Copyright:
        <a class="text-accent2" href="index.html">Pavonne Barbiere</a>
      </div>
      <!-- Copyright -->
    
    </footer>

    <!-- Archivos JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- Plugins -->
    <script src="js/parallax.min.js"></script>
</body>
</html>