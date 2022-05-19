<?php
    ob_start();
    session_start();

    //Titulo
    $pageTitle = 'Barberos';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Extra JS para alertas
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    //Checa si el usuario hizo login
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <div class="container-fluid">
    
            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Barberos</h1>
            </div>

            <?php
                $do = '';

                if(isset($_GET['do']) && in_array($_GET['do'], array('Add','Edit')))
                {
                    $do = htmlspecialchars($_GET['do']);
                }
                else
                {
                    $do = 'Manage';
                }

                if($do == 'Manage')
                {
                    $stmt = $con->prepare("SELECT * FROM empleados");
                    $stmt->execute();
                    $rows_employees = $stmt->fetchAll(); 

                    ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Barberos</h6>
                            </div>
                            <div class="card-body">
                                
                                <!-- Boton añadir barbero -->
                                <a href="employees.php?do=Add" class="btn btn-success btn-sm" style="margin-bottom: 10px;">
                                    <i class="fa fa-plus"></i> 
                                    Añadir Barbero
                                </a>

                                <!-- Tabla Barberos -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Apellido</th>
                                                <th scope="col">Teléfono</th>
                                                <th scope="col">E-mail</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($rows_employees as $employee)
                                                {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo $employee['nombre'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo $employee['apellido'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo $employee['telefono'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo $employee['email'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            $delete_data = "delete_employee_".$employee["empleadoID"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- Boton editar -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Editar">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="employees.php?do=Edit&employee_id=<?php echo $employee['empleadoID']; ?>" style="color: white;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </button>
                                                            </li>

                                                            <!-- Boton borrar -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Borrar">
                                                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>

                                                                <!-- Borrar barbero -->

                                                                <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Borrar Barbero</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                ¿Estás seguro de borrar a este barbero?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                                <button type="button" data-id = "<?php echo $employee['empleadoID']; ?>" class="btn btn-danger delete_employee_bttn">Borrar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    <?php
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php
                }
                elseif($do == 'Add')
                {
                    ?>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Añadir Nuevo Barbero</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="employees.php?do=Add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_fname">Nombre</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_fname']))?htmlspecialchars($_POST['employee_fname']):'' ?>" placeholder="First Name" name="employee_fname">
                                            <?php
                                                $flag_add_employee_form = 0;
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_fname'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                El nombre es requerido.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_lname">Apellido</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_lname']))?htmlspecialchars($_POST['employee_lname']):'' ?>" placeholder="Last Name" name="employee_lname">
                                            <?php
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_lname'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                El apellido es requerido.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_phone">Phone Number</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_phone']))?htmlspecialchars($_POST['employee_phone']):'' ?>" placeholder="Phone number" name="employee_phone">
                                            <?php
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_phone'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Phone number is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label for="employee_email">E-mail</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_email']))?htmlspecialchars($_POST['employee_email']):'' ?>" placeholder="E-mail" name="employee_email">
                                            <?php
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_email'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Email is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boton submit-->

                                <button type="submit" name="add_new_employee" class="btn btn-primary">Add employee</button>

                            </form>

                            <?php

                                /*** Añadir nuevo barbero ***/

                                if(isset($_POST['add_new_employee']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_employee_form == 0)
                                {
                                    $employee_fname = test_input($_POST['employee_fname']);
                                    $employee_lname = $_POST['employee_lname'];
                                    $employee_phone = test_input($_POST['employee_phone']);
                                    $employee_email = test_input($_POST['employee_email']);

                                    try
                                    {
                                        $stmt = $con->prepare("insert into empleados(nombre,apellido,telefono,email) values(?,?,?,?) ");
                                        $stmt->execute(array($employee_fname,$employee_lname,$employee_phone,$employee_email));
                                        
                                        ?> 
                                            <!-- Mnesaje de exito -->

                                            <script type="text/javascript">
                                                swal("Nuevo Barbero","Un nuevo barbero se ha unido a la familia Pavonne", "success").then((value) => 
                                                {
                                                    window.location.replace("employees.php");
                                                });
                                            </script>

                                        <?php

                                    }
                                    catch(Exception $e)
                                    {
                                        echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                            echo 'Error occurred: ' .$e->getMessage();
                                        echo "</div>";
                                    }
                                    
                                }
                            ?>
                        </div>
                    </div>
                    <?php   
                }
                elseif($do == 'Edit')
                {
                    $employee_id = (isset($_GET['employee_id']) && is_numeric($_GET['employee_id']))?intval($_GET['employee_id']):0;

                    if($employee_id)
                    {
                        $stmt = $con->prepare("Select * from empleados where empleadoID = ?");
                        $stmt->execute(array($employee_id));
                        $employee = $stmt->fetch();
                        $count = $stmt->rowCount();

                        if($count > 0)
                        {
                            ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Employee</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="employees.php?do=Edit&employee_id=<?php echo $employee_id; ?>">
                                        <!-- Empleado ID -->
                                        <input type="hidden" name="employee_id" value="<?php echo $employee['empleadoID'];?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_fname">Nombre</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['nombre'] ?>" placeholder="Nombre" name="employee_fname">
                                                    <?php
                                                        $flag_edit_employee_form = 0;
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_fname'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El nombre es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_lname">Apellido</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['apellido'] ?>" placeholder="Apellido" name="employee_lname">
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_lname'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El apellido es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_phone">Teléfono</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['telefono'] ?>"  placeholder="Teléfono" name="employee_phone">
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_phone'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El teléfono es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"> 
                                                    <label for="employee_email">E-mail</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['email'] ?>" placeholder="E-mail" name="employee_email">
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_email'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El email es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- boton submit -->
                                        <button type="submit" name="edit_employee_sbmt" class="btn btn-primary">
                                            Editar Barbero
                                        </button>
                                    </form>
                                    <?php
                                        /*** Editar barbero ***/
                                        if(isset($_POST['edit_employee_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_employee_form == 0)
                                        {
                                            $employee_fname = test_input($_POST['employee_fname']);
                                            $employee_lname = $_POST['employee_lname'];
                                            $employee_phone = test_input($_POST['employee_phone']);
                                            $employee_email = test_input($_POST['employee_email']);
                                            $employee_id = $_POST['employee_id'];

                                            try
                                            {
                                                $stmt = $con->prepare("update empleados set nombre = ?, apellido = ?, telefono = ?, email = ? where empleadoID = ? ");
                                                $stmt->execute(array($employee_fname,$employee_lname,$employee_phone,$employee_email,$employee_id));
                                                
                                                ?> 
                                                    <!-- Mensaje de exito -->

                                                    <script type="text/javascript">
                                                        swal("Barbero actualizado","El barbero ha sido actualizado exitosamente", "success").then((value) => 
                                                        {
                                                            window.location.replace("employees.php");
                                                        });
                                                    </script>

                                                <?php

                                            }
                                            catch(Exception $e)
                                            {
                                                echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                                    echo 'Error occurred: ' .$e->getMessage();
                                                echo "</div>";
                                            }
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        else
                        {
                            header('Location: employees.php');
                            exit();
                        }
                    }
                    else
                    {
                        header('Location: employees.php');
                        exit();
                    }
                }
            ?>
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