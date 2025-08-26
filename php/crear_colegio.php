<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	if (isset($_POST["colegio"])) {
	 	require_once("../php/aut.php");
		include("../conexion/bdd.php");


		$sql = "SELECT id FROM colegios WHERE dane='".$_POST["dane"]."'";

		$req = $bdd->prepare($sql);
		$req->execute();
		$num = $req->rowCount();

		if ($num < 1) {
			
			if ($_POST["empresa"]!=1) {

				$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,dane,direccion,barrio,telefono,id_usuario,cod_zona, sub_zona, responsable, id_calendario) VALUES('".$_POST["colegio"]."', '".$_POST["dane"]."','".$_POST["departamento"]."','".$_POST["ciudad"]."','".$_POST["dane"]."', '".$_POST["direccion"]."', '".$_POST["barrio"]."', '".$_POST["telefono"]."','".$_SESSION['id']."','".$_POST["empresa"]."','".$_POST["zona"]."','".$_POST["responsable"]."', '".$_POST["calendario"]."')";
			
			}else{
				$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,dane,direccion,barrio,telefono,id_usuario,cod_zona, id_calendario) VALUES('".$_POST["colegio"]."', '".$_POST["dane"]."','".$_POST["departamento"]."','".$_POST["ciudad"]."','".$_POST["dane"]."', '".$_POST["direccion"]."', '".$_POST["barrio"]."', '".$_POST["telefono"]."','".$_SESSION['id']."','".$_POST["zona"]."', '".$_POST["calendario"]."')";
			}

			$req = $bdd->prepare($sql);
			$req->execute();

			echo "<script>alert('Colegio creado correctamente');window.location='../agregar_colegio.php'</script>";

		}else{
			echo "<script>alert('DANE ya existente');window.location='../agregar_colegio.php'</script>";
		}

		
		
		
	}
?>