<?php
include("conexion.php");
try{
		$dbh = new PDO("mysql:host=$hostname;dbname=$db", $usr, $pw);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$id = isset($_GET['id']) ? $_GET['id'] : null ;
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$id = isset($_POST['id']) ? $_POST['id'] != '' ? $_POST['id'] : null : null;
		//return;
		if (!isset($_POST['nombre']) and !isset($_POST['puesto']) and !isset($_POST['departamento']) and !isset($_POST['sueldo'])) {
			
			$query = "SELECT Nombre, Departamento, Sueldo, Puesto FROM empleado WHERE Id = '+$id+'";
			$result = $dbh->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$nombre = $row['Nombre'];
			$departamento = $row['Departamento'];
			$sueldo = $row['Sueldo'];
			$puesto = $row['Puesto'];
		}
	}
		catch (PDOException $e){
		echo $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="/catalogo/js/dist/css/bootstrap.min.css">
<script src="/catalogo/js/dist/js/bootstrap.min.js"></script>
<script src="/catalogo/bootstrap-validator/dist/validator.min.js"></script>

<title>Catálogo PHP</title>
</head>
<body>
	<div class="container">
		<header class="h3">Catálogo Empleados</header>
  		<div class="col-md-3">
  			<input id="regresar" value="Regresar a Listado" class="btn btn-primary"/>
  		</div>
  		<div id="jelly" class="col-md-1"></div>	
  		<div class="col-md-9">
  			<form id="foo" method="post" name="form" role="form" data-toggle="validator" class="form-horizontal">
  			<!--<form method="post" name="form" action="sql.php" class="form-horizontal">-->
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="row">
				<div class="form-group">
					<div class="col-md-2">
						<label class="control-label" for="inputnombre">Nombre</label>
					</div>
					<div class="col-md-5">
						<input id="inputnombre" type="text" class="form-control" name="nombre" value="<?php echo $nombre; ?>"
						data-error="Ingrese un nombre válido" pattern="^[a-zA-Z]*$" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-2">
						<label class="control-label" for="inputdepto">Departamento</label>
					</div>
					<div class="col-md-5">
						<input id="inputdepto" type="text" class="form-control" name="departamento" value="<?php echo $departamento; ?>"
						data-error="Ingrese un departamento" required/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-2">
						<label class="control-label" for="inputpuesto">Puesto</label>
					</div>
					<div class="col-md-5">
						<input id="inputpuesto" type="text" class="form-control" name="puesto" value="<?php echo $puesto; ?>" required/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-2">
						<label class="control-label" for="inputsueldo">Sueldo</label>
					</div>
					<div class="col-md-5">
						<input id="inputsueldo" type="text" class="form-control" name="sueldo" value="<?php echo $sueldo; ?>" required/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2 col-md-offset-5" style="text-align: right;">
					<input id="sbmt" type="button" value="Guardar" class="btn btn-primary"/>
					<!--<input type="submit" value="Guardar" class="btn btn-primary"/>-->
				</div>
			</div>
			</form>
  		</div>
		
	</div>
</body>

<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/normalize.css">
<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/ns-default.css">
<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/ns-style-growl.css">
<script src="/catalogo/NotificationStyles/js/modernizr.custom.js"></script>

<script src="/catalogo/NotificationStyles/js/classie.js"></script>
<script src="/catalogo/NotificationStyles/js/notificationFx.js"></script>

<script type="text/javascript">
	$(document).ready(function () {
		$('#regresar').click(function(){
			window.location.href = 'http://localhost/catalogo/listar.php';
		});
		if ($('#foo').validator('validate'))
		{
			$("#sbmit").enlabed
		}
		$("#sbmt").click(function(e){
			var $form = $("#foo");
			var sdata = $form.serialize();
			$.ajax({
				url: "sql.php",
				type: "POST",
				data: sdata,
				cache: false,
				dataType: 'json',
				success: function(d){
					var insert = "<p>Se ha guardado correctamente</p>";
					var update = "<p>Se ha actualizado correctamente</p>";
					var ntf = new NotificationFx({
						wrapper : document.getElementById("jelly"),
						message : "<p>Se ha guardado correctamente</p>",
						layout : 'growl',
						effect : 'jelly',
					type : 'success', // notice, warning, error or success
					ttl : 1500,
					onClose : function() {
						
					}
				});
					ntf.show();
				},
				error: function(x,e){
					if(x.status==0){
						alert('You are offline!!\n Please Check Your Network.');
					}else if(x.status==404){
						alert('Requested URL not found.');
					}else if(x.status==500){
						alert('Internel Server Error.');
					}else if(e=='parsererror'){
						alert('Error.\nParsing JSON Request failed.');
					}else if(e=='timeout'){
						alert('Request Time out.');
					}else {
						alert('Unknow Error.\n'+x.responseText);
					}
					console.log(x);
				}
			});
		});
	})

	function Save(sdata)
	{
	
	}
</script>
</html>