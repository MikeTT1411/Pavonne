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
                <a class="active" href="productos.php">Productos</a>
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
    <div class="parallax-window" data-parallax="scroll" data-bleed="10" data-speed="0.2" data-image-src="img/banner/banner-3.jpg" data-natural-width="1400" style="height: 300px;">
        <h2 id="texto-banner">Nuestros Productos</h2>
    </div>
    <!-- banner -->

    <!-- Productos -->
    <div class="container-fluid p-5">
        <h3 class="text-center text-dark">Compra nuestros productos en nuestra barbería</h3>
    </div>
    
    <div class="container productos">
        <div class="row">
            <div class="col-2">

            </div>
              <div class="col-8">
                  <div class="row row-cols-1 row-cols-md-3" id="result">
                  <?php
                    $consulta= "SELECT * 
                    FROM producto";
                    $datos = mysqli_query($connect, $consulta);
                    while ($fila= mysqli_fetch_array($datos)){
                    ?>
                    <div class="col mb-4">
                        <div class="card  h-100">
                            <img src="img/productos/<?php echo $fila['imagen']?>" class="card-img-top" alt="...">
                            <div class="card-body">
                              <h5><?php echo $fila['nombre'] ?></h5>
                              <h5 style="font-size: 16px"><?php echo $fila['marca'] ?></h5>
                              <p class="text-accent">$<?php echo $fila['precio'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        ?>
                  </div>
              </div>
            <div class="col-2 d-flex justify-content-center">
                <div class="filter-sidebar-left">
                    <div class="title-left">
                        <h3>Marcas</h3>
                    </div>
                    <?php	
                        $consulta2 = "SELECT DISTINCT marca 
                        FROM producto";
                        $datos2 = mysqli_query($connect, $consulta2);
                        while ($row= mysqli_fetch_array($datos2)){          
                        ?>
                        <div class="list-group">
                            <label><input type="checkbox" class="common_selector marca product-check" value="<?php echo $row['marca']; ?>" id="marca"> <?php echo $row['marca']; ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

        

    <!-- Productos -->

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

    <!-- Plugins -->
    <script src="js/parallax.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
              
              $(".product-check").click(function() {
                  $("#loader").show();
  
                  var action = "data";
                  var marca = get_filter_text("marca");
  
                  $.ajax({
                      url:"filter_productos.php",
                      method: 'POST',
                      data: {action:action,marca:marca},
                      success:function(response) {
                          $("#result").html(response);
                          $("#loader").hide();
                      }
                  });
              });
  
              function get_filter_text(text_id) {
                  var filterData = [];
                  $("#" + text_id + ":checked").each(function(){
                      filterData.push($(this).val());
                  });
                  return filterData;
              }

          });
    </script>
</body>
</html>