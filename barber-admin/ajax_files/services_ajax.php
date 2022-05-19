<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	

	if(isset($_POST['do']) && $_POST['do'] == "Delete")
	{
		$service_id = $_POST['service_id'];

        $stmt = $con->prepare("DELETE from servicios where servicioID = ?");
        $stmt->execute(array($service_id));    
	}
	
?>

<?php
	

	if(isset($_POST['do_product']) && $_POST['do_product'] == "Delete_P")
	{
		$product_id = $_POST['product_id'];

        $stmt = $con->prepare("DELETE from producto where productoID = ?");
        $stmt->execute(array($product_id));    
	}
	
?>