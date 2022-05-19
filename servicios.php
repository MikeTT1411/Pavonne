<?php 
include 'connect.php';
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
    <!-- AOS scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
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
                <a class="active" href="servicios.php">Servicios</a>
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

    <!-- banner -->
    <div class="parallax-window" data-parallax="scroll" data-bleed="10" data-speed="0.2" data-image-src="img/banner/banner-2.jpg" style="height: 300px;">
        <h2 id="texto-banner">Nuestros Servicios</h2>
    </div>
    <!-- banner -->

    <!-- Servicios -->
    <div class="container-fluid h-100">
        <?php
           $consulta= "SELECT * 
           FROM serviciosView";
           $datos = mysqli_query($connect, $consulta);
           while ($fila= mysqli_fetch_array($datos)){
        ?>
            <div class="row p-5 servicio justify-content-center align-items-center">
              <div class="col-md-7">
                <img class="img-fluid" src="img/servicios/<?php echo $fila['imagen'] ?>" data-aos="fade-up" data-aos-duration="1500" alt="" id="grey-image">
              </div>
              <div class="col-md-5 text-center" data-aos="fade-up" data-aos-duration="1000">
                <h3><?php echo $fila['nombre'] ?></h3>
                <hr class="solid">
                <p><?php echo $fila['descripcion'] ?></p>
                <p><?php echo $fila['duracion'] ?> minutos</p>
              </div>
            </div>
      <?php
      }
      ?>
    </div>
  
    <!-- Servicios -->

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
    <script src="sticky-scroll"></script>
    <!-- Plugins -->
    <script src="js/parallax.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
</body>
</html>