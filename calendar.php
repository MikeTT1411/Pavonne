<?php
    
    //PHP INCLUDES

	include "connect.php";

	if(isset($_POST['empleado_seleccionado']) && isset($_POST['servicios_seleccionados']))
	{

		?>

        <!-- CALENDAR STYLE -->
        
        <style type="text/css">
            
            .calendario_tab
            {
                background: white;
                margin-top: 5px;
                width: 100%;
                position: relative;
                box-shadow: rgba(60, 66, 87, 0.04) 0px 0px 5px 0px, rgba(0, 0, 0, 0.04) 0px 0px 10px 0px;
                overflow: hidden;
                border-radius: 4px;
            }

            .dia_cita
            {
                width: 15%;
                text-align: center;
                display: flex;
                color: rgb(151, 151, 151);
                font-weight: 700;
                -webkit-box-align: center;
                align-items: center;
                -webkit-box-pack: center;
                justify-content: center;
                font-size: 14px;
                line-height: 1.5;
            }

            .citas_dias
            {
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                display: flex;
                height: 60px;
                position: relative;
                -webkit-box-pack: justify;
                justify-content: space-between;
                padding: 10px;
                border-bottom: 1px solid rgb(229, 229, 229);
            }

            .citas_disponibles
            {
                display: flex;
                -webkit-box-pack: justify;
                justify-content: space-between;
                padding: 10px;
                border-radius: 4px;
            }

            .cita_hora_disponible:hover
            {
                font-weight: 700;
                background-color: #B29657;
                color: white;
            }

            .cita_hora_disponible
            {
                font-size: 14px;
                line-height: 1.3;
                cursor: pointer;
                border-style: solid;
                padding-left: 12px;
                padding-right: 12px;
                padding-top: 15px;
                padding-bottom: 15px;
                border-radius: 8px;
            }


            input[type="radio"] 
            {
                display: none;
            }

            input[type="radio"]:checked + label 
            {
                font-weight: 700;
                background-color: #B29657;
                color: white;
            }

            .citas_disponibles_colum
            {
                width: 15%;
                text-align: center;
            }

        </style>

        <!-- END CALENDAR STYLE -->

        <!-- START CALENDAR SLOT -->

        <div class="calendar_slots" style="min-width: 600px;">

            <!-- NEXT 10 DAYS -->

            <div class="citas_dias">
                <?php
                    date_default_timezone_set('America/Mexico_City');
                    $fecha_cita = date('Y-m-d H:i:00');

                    for($i = 0; $i < 10; $i++)
                    {
                        if($i==0) {
                            $fecha_cita = date('Y-m-d', strtotime($fecha_cita));
                        } else {
                            $fecha_cita = date('Y-m-d', strtotime($fecha_cita . "+1 day"));
                        }
                        echo "<div class = 'dia_cita'>";
                            echo date('D', strtotime($fecha_cita));
                            echo "<br>";
                            echo date('d', strtotime($fecha_cita))." ".date('M', strtotime($fecha_cita));
                        echo "</div>";
                    } 
                ?>
            </div>

            <!-- DAY HOURS -->

            <div class = 'citas_disponibles'>
                <?php

                    //SELECTED SERVICES
		            $servicios_deseados = $_POST['servicios_seleccionados'];
		            
                    //SELECTED EMPLOYEE
		            $empleado_seleccionado = $_POST['empleado_seleccionado'];

            		//Services Duration - End time expected
		            $sum_duracion = 0;
		            
                    foreach($servicios_deseados as $servicio)
		            {
		                
		                $stmtServices = $con->prepare("select duracion from servicios where servicioID = ?");
		                $stmtServices->execute(array($servicio));
		                $rowS =  $stmtServices->fetch();
		                $sum_duracion += $rowS['duracion'];
		                
		            }
            
            
		            $sum_duracion = date('H:i',mktime(0,$sum_duracion));
		            $secs = strtotime($sum_duracion)-strtotime("00:00:00");


                    $hora_abierto = date('H:i',mktime(9,0,0));
                    
                    $hora_cierre = date('H:i',mktime(22,0,0));

                    $start = $hora_abierto;

                    $secs = strtotime($sum_duracion)-strtotime("00:00:00");
                    $result = date("H:i:s",strtotime($start)+$secs);

                    date_default_timezone_set('America/Mexico_City');
                    
                    $fecha_cita = date('Y-m-d');
                    $fecha_cita2 = date('Y-m-d H:i:s');
                    //$fecha_actual = strtotime($fecha_cita2);
                    //echo $fecha_cita2;
                    for($i = 0; $i < 10; $i++)
                    {
                        echo "<div class='citas_disponibles_colum'>";
                            if($i==0) {
                                $fecha_cita = date('Y-m-d', strtotime($fecha_cita));
                            } else {
                                $fecha_cita = date('Y-m-d', strtotime($fecha_cita . "+1 day"));
                            }
                            $start = $hora_abierto;
                            $secs = strtotime($sum_duracion)-strtotime("00:00:00");
                            $result = date("H:i:s",strtotime($start)+$secs);
                            //echo $fecha_cita." ".$result;
                            $diaID = date('w',strtotime($fecha_cita));
                            
                            while($start >= $hora_abierto && $result <= $hora_cierre)
                            {
                                // Check If the employee is available

                                $stmt_emp = $con->prepare("
                                    Select empleadoID
                                    from horarioempleados
                                    where empleadoID = ?
                                    and diaID = ?
                                    and ? between hora_inicio and hora_fin
                                    and ? between hora_inicio and hora_fin
                                       
                                ");
                                $stmt_emp->execute(array($empleado_seleccionado,$diaID,$start, $result));
                                $emp = $stmt_emp->fetchAll();

                                //If employee is available

                                if($stmt_emp->rowCount() != 0)
                                {

                                    //Check If there are no intersecting appointments with the current one
                                    $stmt = $con->prepare("
                                        Select * 
                                        from citas a
                                        where
                                            date(hora_inicio) = ?
                                            and
                                            a.empleadoID = ?
                                            and
                                            status = 0
                                            and
                                            (   
                                                time(hora_inicio) between ? and ?
                                                or
                                                time(hora_terminado) between ? and ?
                                            )
                                    ");
                                    
                                    $stmt->execute(array($fecha_cita,$empleado_seleccionado,$start,$result,$start,$result));
                                    $rows = $stmt->fetchAll();
                        
                                    if($stmt->rowCount() != 0)
                                    {
                                        //Show blank cell
                                    }
                                    else
                                    {
                                        if($fecha_cita2 > $fecha_cita." ".$result){
                                            //Show blank cell
                                        }else{
                                            ?>
                                                <input type="radio" id="<?php echo $fecha_cita." ".$start; ?>" name="fecha_deseada" value="<?php echo $fecha_cita." ".$start." ".$result; ?>">
                                                <label class="cita_hora_disponible" for="<?php echo $fecha_cita." ".$start; ?>"><?php echo $start; ?></label>
                                            <?php
                                        }
                                    }
                                }
                                else
                                {
                                    //Show Blank cell
                                }
                                

                                $start = strtotime("+15 minutes", strtotime($start));
                                $start =  date('H:i', $start);

                                $secs = strtotime($sum_duracion)-strtotime("00:00:00");
                                $result = date("H:i",strtotime($start)+$secs);
                            }

                        echo "</div>";
                    }
                ?>
            </div>
        </div>
	<?php
	}
    else
    {
        header('location: index.php');
        exit();
    }
?>