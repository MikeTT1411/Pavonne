<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	
	if(isset($_POST['do']) && $_POST['do'] == "Add")
	{
        $category_name = test_input($_POST['category_name']);

        $checkItem = checkItem("categoria","categoriasservicios",$category_name);

        if($checkItem != 0)
        {
            $data['alert'] = "Warning";
            $data['message'] = "Esta categoría ya existe";
            echo json_encode($data);
            exit();
        }
        elseif($checkItem == 0)
        {
        	//insertar en base de datos
            $stmt = $con->prepare("insert into categoriasservicios(categoria) values(?) ");
            $stmt->execute(array($category_name));

            $data['alert'] = "Success";
            $data['message'] = "La nueva categoría ha sido insertada exitosamente";
            echo json_encode($data);
            exit();
        }
            
	}

    if(isset($_POST['action']) && $_POST['action'] == "Delete")
	{
        $category_id = $_POST['category_id'];
        
        try
        {
            $con->beginTransaction();

            $stmt_services = $con->prepare("select servicioID from servicios where categoriaID = ?");
            $stmt_services->execute(array($category_id));
            $services = $stmt_services->fetchAll();
            $services_count = $stmt_services->rowCount();

            if($services_count > 0)
            {
                $stmt_service_uncategorized = $con->prepare("select categoriaID from categoriasservicios where LOWER(categoria) = ?");
                $stmt_service_uncategorized->execute(array("uncategorized"));
                $uncategorized_id = $stmt_service_uncategorized->fetch();

                foreach($services as $service)
                {
                    $stmt_update_service = $con->prepare("UPDATE servicios set categoriaID = ? where servicioID = ?");
                    $stmt_update_service->execute(array($uncategorized_id["categoriaID"], $service["servicioID"]));
                }
            }

            $stmt = $con->prepare("DELETE from categoriasservicios where categoriaID = ?");
            $stmt->execute(array($category_id));
            $con->commit();
            $data['alert'] = "Success";
            $data['message'] = "La categoría se ha borrado con éxito";
            echo json_encode($data);
            exit();

            
        }
        catch(Exception $exp)
        {
            echo $exp->getMessage() ;
            $con->rollBack();
            $data['alert'] = "Warning";
            $data['message'] =  $exp->getMessage() ;
            echo json_encode($data);
            exit();
        }

    }
    
    if(isset($_POST['action']) && $_POST['action'] == "Edit")
	{
        $category_id = $_POST['category_id'];
        $category_name = test_input($_POST['category_name']);

        $checkItem = checkItem("categoria","categoriasservicios",$category_name);

        if($checkItem != 0)
        {
            $data['alert'] = "Warning";
            $data['message'] = "Esta categoría ya existe";
            echo json_encode($data);
            exit();
        }
        elseif($checkItem == 0)
        {

            try
            {
                $stmt = $con->prepare("UPDATE categoriasservicios set categoria = ? where categoriaID = ?");
                $stmt->execute(array($category_name, $category_id));

                $data['alert'] = "Success";
                $data['message'] = "La categoría se ha actualizado exitosamente";
                echo json_encode($data);
                exit();
            }   
            catch(Exception $e)
            {
                $data['alert'] = "Warning";
                $data['message'] = $e->getMessage();
                echo json_encode($data);
                exit();
            }

            
        }
    }
	
?>