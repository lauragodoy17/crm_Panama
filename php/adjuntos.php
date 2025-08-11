<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	include("../conexion/bdd.php");

	header("Content-Type:text/html;charset=utf-8");	
	$dir_subida = '../adjuntos/';
	$nombre_archivo=uniqid()."_".$_FILES['lista']['name'];
	$fichero_subido = $dir_subida . basename($nombre_archivo);

	echo '<pre>';
	if (move_uploaded_file($_FILES['lista']['tmp_name'], $fichero_subido)) {

		$sql_z = "INSERT INTO adjuntos(id_colegio,id_periodo,adjunto,nombre) VALUES('".$_POST['colegio']."', '".$_POST['periodo']."','".$nombre_archivo."','".$_POST['nombre']."')";

		$query_z = $bdd->prepare( $sql_z );
		if ($query_z == false) {
			print_r($bdd->errorInfo());
			die ('Erreur prepare');
		}
		$sth_z = $query_z->execute();
		if ($sth_z == false) {
			print_r($query_z->errorInfo());
			die ('Erreur execute');
		}

	}else {

		echo "Ha ocurrido un error, vuelva a intentarlo, si el error persiste comuniquese con el desarrollador";
	}

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=adjuntos');

?>