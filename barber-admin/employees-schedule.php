<?php
    session_start();

    //Titulo
    $pageTitle = 'Horario de Barberos';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Extra JS para alertas
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    //Checa si el user hizo login
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <div class="container-fluid">
    
            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Horario de Barberos</h1>
            </div>

            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Horario de Barberos</h6>
                </div>
                <div class="card-body">
                    <div class="sb-entity-selector" style="max-width:300px;">
                        <form action="employees-schedule.php" method="POST">
                            <div class="form-group">
                                <label class="control-label" for="emloyee_schedule_select">
                                    Selecciona el barbero:
                                </label>
                                <div style="display:inline-block;margin-bottom: 10px;">
                                    <?php 
                                        $stmt = $con->prepare('select * from empleados');
                                        $stmt->execute();
                                        $employees = $stmt->fetchAll();
                                    
                                        echo "<select class='form-control' name='employee_selected'>";
                                            foreach ($employees as $employee) 
                                            {
                                                echo "<option value=".$employee['empleadoID']." ".((isset($_POST['employee_selected']) && $_POST['employee_selected'] == $employee['empleadoID'])?'selected':'').">".$employee['nombre']." ".$employee['apellido']."</option>";
                                            }
                                        echo "</select>";                                    
                                    ?>
                                </div>
                                <button type="submit" name="show_schedule_sbmt" class="btn btn-primary">Mostrar Horario</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="alert alert-info">
                        Configura la semana aquí. Simplemente selecciona la hora inicio y hora de salida para aplicar los horas de los barberos.
                    </div>
                    
                    
                    <!-- HORARIO -->
                    
                    <div class="sb-content" style="min-height: 500px;">
                        <?php

                            /** MUESTRA EL HORARIO AL DAR CLICK EN SUBMIT **/

                            if(isset($_POST['show_schedule_sbmt']))
                            {
                        ?>
                                <form method="POST" action="employees-schedule.php">
                                    <input type="hidden" name="employee_id" value="<?php echo $_POST['employee_selected'];?>" hidden>     
                                    <div class="worktime-days">
                                        <?php
                                            $employee_id = $_POST['employee_selected'];
                                            $stmt = $con->prepare('select * from empleados e, horarioempleados es where es.empleadoID = e.empleadoID and e.empleadoID = ?');
                                            $stmt->execute(array($employee_id));
                                            $employees = $stmt->fetchAll();
            
                                            $days = array("1"=>"Lunes",
                                                "2"=>"Martes",
                                                "3"=>"Miércoles",
                                                "4"=>"Jueves",
                                                "5"=>"Viernes",
                                                "6"=>"Sábado",
                                                "7"=>"Domingo") ;
                                        
                                            //Dias disponibles
                                            $av_days = array();
                                            foreach($employees as $employee)
                                            {
                                                $av_days[] = $employee['diaID'];
                                            }
                                        
                                            foreach($days as $key => $value)
                                            {
                                                echo "<div class='worktime-day row'>";
                                                
                                                if(in_array($key,$av_days))
                                                {
                                                    echo "<div class='form-group  col-md-4'>";
                                                        echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch' type='checkbox' checked>";
                                                        echo "<span class='day-name'>";                
                                                            echo $value;
                                                        echo "</span>";
                                                    echo "</div>";
                                                    
                                                    foreach($employees as $employee)
                                                    {
                                                        if(in_array($key,$av_days) && $employee['diaID'] == $key)
                                                        {
                                                            echo "<div class='time_ col-md-8 row'>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-from' value='".$employee['hora_inicio']."' class='form-control'>";
                                                            echo "</div>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-to' value='".$employee['hora_fin']."'  class='form-control'>";
                                                            echo "</div>";
                                                            echo "</div>";
                                                        
                                                        }
                                                    
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<div class='form-group  col-md-4'>";
                                                    echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch' type='checkbox'>";
                                                    echo "<span class='day-name'>";                
                                                    echo $value;
                                                    echo "</span>";
                                                    echo "</div>";
                                                    
                                                    echo "<div class='time_ col-md-8 row' style='display:none;'>";
                                                    echo "<div class='form-group col-md-6'>";
                                                    echo "<input type='time' name='".$value."-from' value = '09:00' class='form-control'>";
                                                    echo "</div>";
                                                    echo "<div class='form-group col-md-6'>";
                                                    echo "<input type='time' name='".$value."-to' value = '18:00' class='form-control'>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    
                                                }
                                                
                                                echo "</div>";
                                            }
                                        ?>
                                    </div>

                                    <!-- boton para guardar -->

                                    <div class="form-group">
                                        <button type="submit" name="save_schedule_sbmt" class="btn btn-info">Guardar</button>
                                    </div>
                                </form>
                        <?php
                            }
                        ?>
                    </div>

                    <?php

                        if(isset($_POST['save_schedule_sbmt']))
                        {
                            $days = array("1"=>"Lunes",
                               "2"=>"Martes",
                               "3"=>"Miércoles",
                               "4"=>"Jueves",
                               "5"=>"Viernes",
                               "6"=>"Sábado",
                               "7"=>"Domingo") ;
                            $stmt = $con->prepare("delete from horarioempleados where empleadoID = ?");
                            $stmt->execute(array($_POST['employee_id']));
                            
                            foreach($days as $key=>$value)
                            {
                                if(isset($_POST[$value]))
                                {   
                                    $stmt = $con->prepare("insert into horarioempleados(empleadoID,diaID,hora_inicio,hora_fin) values(?, ?, ?,?)");
                                    $stmt->execute(array($_POST['employee_id'],$key,$_POST[$value.'-from'],$_POST[$value.'-to']));
                                    
                                    $message = "El horario del barbero se ha actualizado correctamente";
                                    
                                    ?>

                                        <script type="text/javascript">
                                            swal("Horario del Barbero","El horario del barbero se ha actualizado correctamente", "success").then((value) => {}); 
                                        </script>

                                    <?php
                                }
                            }
                        }
                    ?>
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