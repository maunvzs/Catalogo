<?php
if (isset($_POST['id']))
{
	include("conexion.php");
	try{
		$dbh = new PDO("mysql:host=$hostname;dbname=$db", $usr, $pw);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$id = $_POST['id'];
		$sql = "DELETE FROM empleado WHERE Id = '+$id+'";
		$query = $dbh->prepare($sql);
		if ($query->execute())
		{
			echo $id;
		}
		else{
			echo $query->errorCode();
		}
	}
	catch (PDOException $e){
		echo $e->getMessage();
	}
}

?>