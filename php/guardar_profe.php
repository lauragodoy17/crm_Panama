<?php

	include("../conexion/bdd.php");

	foreach ($_POST["profe"] as $profes => $profe) {

		list($nombre,$apellido,$correo, $telefono, $area) = explode("/", $profe);
			
		if ($profe !="") {
			
			$sql_p = "INSERT INTO trabajadores_colegios(id_colegio, nombre, apellido, email, telefono, area, cargo) VALUES('{$_POST['id_colegio']}', '{$nombre}','{$apellido}','{$correo}','{$telefono}','{$area}', '6')";
				
				
			$query_p = $bdd->prepare( $sql_p );
			if ($query_p == false) {
				print_r($bdd->errorInfo());
				die ('Erreur prepare');
			}
			$sth_p = $query_p->execute();
			if ($sth_p == false) {
				print_r($query_p->errorInfo());
				die ('Erreur execute');
			}

		}
		

	}

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=info_contac');

?>