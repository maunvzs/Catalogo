<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="/catalogo/js/dist/css/bootstrap.min.css">
<script src="/catalogo/js/dist/js/bootstrap.min.js"></script>
<script src="/catalogo/Bootstrap-Confirmation/bootstrap-confirmation.min.js"></script>

<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/normalize.css">
<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/ns-default.css">
<link rel="stylesheet" type="text/css" href="/catalogo/NotificationStyles/css/ns-style-growl.css">
<script src="/catalogo/NotificationStyles/js/modernizr.custom.js"></script>
<title>Catálogo PHP</title>
</head>
<body>
	<div id="notif" class="col-xs-push-11"></div>
	<div class="container">
		<header class="h3">Catálogo Empleados</header>

		<?php
			include("conexion.php");
			try{
				$dbh = new PDO("mysql:host=$hostname;dbname=$db;charset=utf8", $usr, $pw);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$id = isset($_GET['id']) ? $_GET['id'] : null;
				if ($id != null)
				{
					$sql = "DELETE FROM empleado WHERE Id = '+$id+'";
					$query = $dbh->prepare($sql);
					if ($query->execute())
					{
						echo "Borrado";
					}
					else{
						echo $query->errorCode();
					}
				}
				

				$query = "SELECT Id, Nombre, Departamento, Sueldo, Puesto FROM empleado";
				print "<table class='table table-hover' id='listado'> \n";
				$result = $dbh->query($query);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				print "<tr> \n";

				foreach ($row as $field => $value) {
					print "<th>$field</th> \n";
				}
				print "</tr> \n";

				$data = $dbh->query($query);
				$data->setFetchMode(PDO::FETCH_ASSOC);

				foreach ($data as $row) {
					print "<tr> \n";
					foreach ($row as $name=>$value) {
						print "<td>$value</td> \n";
						}
						print "</tr> \n";
					}
					print "</table> \n";
				} catch (PDOException $e){
				echo $e->getMessage();
				}
		?>
		<div class="row">
			<div class="col-lg-2">
				<input id="nuevo" value="Nuevo" class="btn btn-primary"/>
			</div>
		</div>
	</div>
	<script src="/catalogo/NotificationStyles/js/classie.js"></script>
	<script src="/catalogo/NotificationStyles/js/notificationFx.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#listado tr:first").append("<th>Accion</th>");
			$("#listado tr:gt(0)").append("<td><input type='button' value='Editar' class='btn btn-info' onclick='Editar(this)'></td>");
			$("#listado tr:first").append("<th>Accion</th>");
			$("#listado tr:gt(0)").append("<td><button class='btn btn-danger' data-toggle='confirmation' data-btn-ok-label='Ok' data-btn-ok-icon='glyphicon glyphicon-share-alt' data-btn-ok-class='btn-success' data-btn-cancel-label='No' data-btn-cancel-icon='glyphicon glyphicon-ban-circle' data-btn-cancel-class='btn-danger' data-title='¿Desea eliminar el regitro?'>Eliminar</button></td>");
			$('#nuevo').click(function(){
				window.location.href = 'http://localhost/catalogo/form.php';
			});
				
			$('[data-toggle="confirmation"]').confirmation({
				placement: 'right',
				singleton: true,
				popout: true,
				onConfirm: function(){
					var td = $(this).parent().parent().find('td');
					$.ajax({
						url: "delete.php",
						type: "POST",
						data: { id: td.eq(0).html() },
						cache: false,
						/*dataType: 'json',*/
						success: function(d){
							var ntf = new NotificationFx({
								/*wrapper : document.getElementById("jelly"),*/
								message : "<p>Se ha eliminado correctamente</p>",
								layout : 'growl',
								effect : 'jelly',
							type : 'success', // notice, warning, error or success
							ttl : 1500
							})
							td.parent().remove();
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
					//window.location.href = "listar.php?id="+$(o).eq(0).html()+"";
				}/*,
				onCancel: function() {
					var ntf = new NotificationFx({
						message : '<p>Se ha actualizado el regitro</p>',
							layout : 'growl',
							effect : 'jelly',
							type : 'success', // notice, warning, error or success
							ttl : 1500,
							onClose : function() {
								
							}
					});
					ntf.show();
				}*/
			});
		});

		function Editar (btn) {
			var id = $(btn).parent().parent().find('td').eq(0).html();
			window.location.href = "form.php?id="+id+"";
		};

		function Eliminar (btn) {
			var o = $(btn).parent().parent().find('td');
			//if (confirm("Desea eliminar al empleado: " + $(o).eq(1).html() + " ?"))
			window.location.href = "listar.php?id="+$(o).eq(0).html()+"";
		};
	</script>
</body>
</html>