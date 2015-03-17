<?php
if (isset($_POST['id']) and isset($_POST['nombre']) and isset($_POST['puesto']) and isset($_POST['departamento']) and isset($_POST['sueldo']))
{
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$puesto = $_POST['puesto'];
	$departamento = $_POST['departamento'];
	$sueldo = $_POST['sueldo'];
	$arr = array('id' => $id ,
				'nombre' => $nombre,
				'departamento' => $departamento,
				'puesto' => $puesto,
				'sueldo' => $sueldo);
	echo Save($arr);
}

function Save($obj)
{
	include("conexion.php");
	try{
		$dbh = new PDO("mysql:host=$hostname;dbname=$db", $usr, $pw);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$id = !empty($obj['id']) ? $obj['id'] : "0";
		$query = "SELECT IF( EXISTS( SELECT Id FROM empleado WHERE Id = $id), 1, 0)";
		$result = $dbh->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		foreach ($row as $field => $value) {
			switch ($value) {
					//print $value;
				case '0':
					$flujo = Validate($obj);
					if (!empty($flujo))
					{
						echo var_dump($flujo);
						exit;
					}
					$sql = "INSERT INTO empleado(Nombre, Puesto, Departamento, Sueldo)
							VALUES(:nombre, :puesto, :departamento, :sueldo)";
					$query = $dbh->prepare($sql);
					$query->bindParam(':nombre', $valor['nombre'], PDO::PARAM_STR);
					$query->bindParam(':puesto', $valor['puesto'], PDO::PARAM_STR);
					$query->bindParam(':departamento', $valor['departamento'], PDO::PARAM_STR);
					$query->bindParam(':sueldo', $valor['sueldo'], PDO::PARAM_STR);
					if ($query->execute())
					{
						echo json_encode($obj);
						exit;
					}
					break;
				
				case '1':
					//print $value;
					$sql = "UPDATE empleado SET Nombre = :nombre, Puesto = :puesto, Departamento = :departamento, Sueldo = :sueldo
							WHERE Id = :id";
					$query = $dbh->prepare($sql);
					$query->bindParam(':id', $obj['id'], PDO::PARAM_STR);
					$query->bindParam(':nombre', $obj['nombre'], PDO::PARAM_STR);
					$query->bindParam(':puesto', $obj['puesto'], PDO::PARAM_STR);
					$query->bindParam(':departamento', $obj['departamento'], PDO::PARAM_STR);
					$query->bindParam(':sueldo', $obj['sueldo'], PDO::PARAM_STR);
					if ($query->execute())
					{
						echo json_encode($obj);
						exit;
					}
					break;
			}
		}
	}
	catch (PDOException $e){
		echo $e->getMessage();
	}
}

function Validate($o)
{
	//return array_search("", $o) !== false;
	unset($o['id']);
	$key = array_search("", $o);
	if ($key != false)
		return $key;
	else
		return "";
}
?>