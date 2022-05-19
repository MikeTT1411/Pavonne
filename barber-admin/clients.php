<?php
    session_start();

     //Titulo
    $pageTitle = 'Clientes';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Checa si el usuario ya hizo login
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <div class="container-fluid">
    
            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Clientes</h1>
            </div>

            <!-- Tabla de clientes -->
            <?php
                $stmt = $con->prepare("SELECT * FROM clientes");
                $stmt->execute();
                $rows_clients = $stmt->fetchAll(); 
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Clientes</h6>
                </div>
                <div class="card-body">
                    
                    <!-- Tabla de clientes -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">ID#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">E-mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($rows_clients as $client)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $client['clienteID'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['nombre'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['apellido'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['telefono'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['email'];
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
  
<?php 
        
        //Footer
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>