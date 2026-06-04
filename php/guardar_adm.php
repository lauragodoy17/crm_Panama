<?php

	include("../conexion/bdd.php");

	foreach ($_POST["adm"] as $adms => $adm) {

		if ($adm == "") continue;

		$parts = explode("/", $adm);
		if (count($parts) < 5) continue;

		list($nombre,$apellido,$correo, $telefono, $cargo) = $parts;

		if ($adm !="") {
			
			$sql_p = "INSERT INTO trabajadores_colegios(id_colegio, nombre, apellido, email, telefono, cargo) VALUES('{$_POST['id_colegio']}', '{$nombre}','{$apellido}','{$correo}','{$telefono}','{$cargo}')";
				
				
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