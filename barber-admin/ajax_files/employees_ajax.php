<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	

	if(isset($_POST['do']) && $_POST['do'] == "Delete")
	{
		$employee_id = $_POST['employee_id'];

        $stmt = $con->prepare("DELETE from empleados where empleadoID = ?");
        $stmt->execute(array($employee_id));    
	}
	
?>