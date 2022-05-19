<?php
    ob_start();
    session_start();

    //Titulo
    $pageTitle = 'Productos';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <div class="container-fluid">
    
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Productos</h1>
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
                    $stmt = $con->prepare("SELECT * FROM producto");
                    $stmt->execute();
                    $rows_products = $stmt->fetchAll();
                ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Productos</h6>
                        </div>
                        <div class="card-body">
                            
                            <a href="products.php?do=Add" class="btn btn-success btn-sm" style="margin-bottom: 10px;">
                                <i class="fa fa-plus"></i> 
                                Añadir Productos
                            </a>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Imágen</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Configurar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rows_products as $product)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo '<img class="img-fluid" src="../img/productos/'.$product['imagen'].'" alt="" style="width: 100px; height: 100px;">';
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $product['nombre'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $product['marca'];
                                                echo "</td>";
                                                echo "<td style = 'width:30%'>";
                                                    echo $product['descripcion'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "$".$product['precio'];
                                                echo "</td>";
                                                echo "<td>";
                                                $delete_data = "delete_".$product["productoID"];
                                                ?>
                                                    <ul class="list-inline m-0">

                                                        <li class="list-inline-item" data-toggle="tooltip" title="Editar">
                                                            <button class="btn btn-success btn-sm rounded-0">
                                                                <a href="products.php?do=Edit&product_id=<?php echo $product['productoID']; ?>" style="color: white;">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </button>
                                                        </li>

                                                        <li class="list-inline-item" data-toggle="tooltip" title="Borrar">
                                                            <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>

                                                            <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Borrar Producto</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            ¿Estás seguro de que quieres borrar este Producto "<?php echo $product['nombre']; ?>"?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                            <button type="button" data-id = "<?php echo $product['productoID']; ?>" class="btn btn-danger delete_product_bttn">Borrar</button>
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
                    <?php
                }
                elseif($do == 'Add')
                {
                    ?>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Añadir nuevo producto</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="products.php?do=Add" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_name">Producto</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['product_name']))?htmlspecialchars($_POST['product_name']):'' ?>" placeholder="Nombre del producto" name="product_name">
                                            <?php
                                                $flag_add_product_form = 0;
                                                if(isset($_POST['add_new_product']))
                                                {
                                                    if(empty(test_input($_POST['product_name'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                El producto es requerido.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_marca">Marca</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['product_marca']))?htmlspecialchars($_POST['product_marca']):'' ?>" placeholder="Marca del producto" name="product_marca">
                                            <?php
                                                $flag_add_product_form = 0;
                                                if(isset($_POST['add_new_product']))
                                                {
                                                    if(empty(test_input($_POST['product_marca'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La marca del producto es requerida.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_price">Precio($)</label>
                                            <input type="number" class="form-control" value="<?php echo (isset($_POST['product_price']))?htmlspecialchars($_POST['product_price']):'' ?>" placeholder="Precio" name="product_price">
                                            <?php

                                                if(isset($_POST['add_new_product']))
                                                {
                                                    if(empty(test_input($_POST['product_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                El precio del producto es requerido.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                    elseif(!is_numeric(test_input($_POST['product_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Precio Inválido.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_image">Imagen</label>
                                            <input type="file" class="form-control" name="product_image" value="<?php echo (isset($_POST['product_image']))?htmlspecialchars($_POST['product_image']):'' ?>" accept=".jpg, .jpeg, .png" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="product_description">Descripción</label>
                                            <textarea class="form-control" name="product_description" style="resize: none;"><?php echo (isset($_POST['product_description']))?htmlspecialchars($_POST['product_description']):''; ?></textarea>
                                            <?php

                                                if(isset($_POST['add_new_product']))
                                                {
                                                    if(empty(test_input($_POST['product_description'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La descripción del producto es requerida.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                    elseif(strlen(test_input($_POST['product_description'])) > 250)
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La descripción debe de contar con debajo de 250 letras.
                                                            </div>
                                                        <?php

                                                        $flag_add_product_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="add_new_product" class="btn btn-primary">Añadir producto</button>

                            </form>

                            <?php

                                if(isset($_POST['add_new_product']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_product_form == 0)
                                {
                                    $product_name = test_input($_POST['product_name']);
                                    $product_marca = test_input($_POST['product_marca']);
                                    $product_price = test_input($_POST['product_price']);
                                    $product_description = test_input($_POST['product_description']);
                                    $product_image = $_FILES["product_image"]["name"];

                                    $extension = substr($product_image,strlen($product_image)-4,strlen($product_image));
                                    $imgnewfile = md5($product_image).time().$extension;
                                    move_uploaded_file($_FILES["product_image"]["tmp_name"],"../img/productos/".$imgnewfile);

                                    try
                                    {
                                        $stmt = $con->prepare("insert into producto(nombre,descripcion,precio,marca,imagen) values(?,?,?,?,?) ");
                                        $stmt->execute(array($product_name,$product_description,$product_price,$product_marca,$imgnewfile));
                                        
                                        ?> 

                                            <script type="text/javascript">
                                                swal("Nuevo Producto","Un nuevo producto ha sido creado exitosamente", "success").then((value) => 
                                                {
                                                    window.location.replace("products.php");
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
                elseif($do == "Edit")
                {
                    $product_id = (isset($_GET['product_id']) && is_numeric($_GET['product_id']))?intval($_GET['product_id']):0;

                    if($product_id)
                    {
                        $stmt = $con->prepare("Select * from producto where productoID = ?");
                        $stmt->execute(array($product_id));
                        $product = $stmt->fetch();
                        $count = $stmt->rowCount();

                        if($count > 0)
                        {
                            ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Editar Producto</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="products.php?do=Edit&product_id=<?php echo $product_id; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="product_id" value="<?php echo $product['productoID'];?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="product_name">Producto</label>
                                                    <input type="text" class="form-control" value="<?php echo $product['nombre'] ?>" placeholder="Producto" name="product_name">
                                                    <?php
                                                        $flag_edit_product_form = 0;

                                                        if(isset($_POST['edit_product_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['product_name'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El nombre del producto es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                            
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="product_marca">Marca</label>
                                                    <input type="text" class="form-control" value="<?php echo $product['marca'] ?>" placeholder="Marca del producto" name="product_marca">
                                                    <?php
                                                        $flag_edit_product_form = 0;
                                                        if(isset($_POST['edit_product_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['product_marca'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La marca del producto es requerida.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="product_price">Precio($)</label>
                                                    <input type="text" class="form-control" value="<?php echo $product['precio'] ?>" placeholder="Product Price" name="product_price">
                                                    <?php

                                                        if(isset($_POST['edit_product_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['product_price'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        El precio es requerido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                            elseif(!is_numeric(test_input($_POST['product_price'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Precio inválido.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <img src="../../img/productos/<?php echo $product['imagen']?>" style="width:50%;" alt="product_image">
                                                <br>
                                                <label for="product_image">Imagen</label>
                                                <input type="file" class="form-control" value="<?php echo $product['imagen'] ?>" name="product_image" accept=".jpg, .jpeg, .png" required>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="product_description">Descripción</label>
                                                    <textarea class="form-control" name="product_description" style="resize: none;"><?php echo $product['descripcion']; ?></textarea>
                                                    <?php

                                                        if(isset($_POST['edit_product_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['product_description'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La descripción es requerida.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                            elseif(strlen(test_input($_POST['product_description'])) > 250)
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La longitud de la descripción debe ser debajo de 250 letras.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_product_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" name="edit_product_sbmt" class="btn btn-primary">Guardar</button>
                                    </form>
                                    
                                    <?php
                                        if(isset($_POST['edit_product_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_product_form == 0)
                                        {
                                            $product_id = $_POST['product_id'];
                                            $product_name = test_input($_POST['product_name']);
                                            $product_marca = test_input($_POST['product_marca']);
                                            $product_price = test_input($_POST['product_price']);
                                            $product_image = $_FILES["product_image"]["name"];
                                            $product_description = test_input($_POST['product_description']);

                                            $extension = substr($product_image,strlen($product_image)-4,strlen($product_image));
                                            $imgnewfile = md5($product_image).time().$extension;
                                            move_uploaded_file($_FILES["product_image"]["tmp_name"],"../img/productos/".$imgnewfile);
                                            
                                            try
                                            {
                                                $stmt = $con->prepare("update producto set nombre = ?, descripcion = ?, precio = ?, imagen=?, marca = ? where productoID = ? ");
                                                $stmt->execute(array($product_name,$product_description,$product_price,$imgnewfile,$product_marca,$product_id));
                                                
                                                ?> 

                                                    <script type="text/javascript">
                                                        swal("Producto Actualizado","El producto ha sido actualizado correctamente", "success").then((value) => 
                                                        {
                                                            window.location.replace("products.php");
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
                            header('Location: products.php');
                            exit();
                        }
                    }
                    else
                    {
                        header('Location: products.php');
                        exit();
                    }
                }
            ?>
        </div>
  
<?php 
        
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>