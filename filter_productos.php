<?php
    require('connect.php');

    if(isset($_POST['action'])) {
        $sql = "SELECT * FROM producto WHERE marca !=''";

        if(isset($_POST['marca'])) {
            $marca = implode("','", $_POST['marca']);
            $sql .= "AND marca IN('".$marca."')";
        }

        $result = $connect->query($sql);
        $output = "";

        if($result->num_rows>0) {
            while($fila=$result->fetch_assoc()) {
                $output .= '
                <div class="col mb-4">
                        <div class="card  h-100">
                            <img src="img/productos/'.$fila['imagen'].'" class="card-img-top" alt="...">
                            <div class="card-body">
                              <h5>'.$fila['nombre'].'</h5>
                              <h5 style="font-size: 16px">'.$fila['marca'].'</h5>
                              <p class="text-accent">$'.$fila['precio'].'</p>
                            </div>
                        </div>
                    </div>';
            }
        }
        else {
            $output = "<h3>No se encontraron los productos!</h3>";
        }
        echo $output;
    }
?>