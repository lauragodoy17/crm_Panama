<?php

	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);
	require_once('../conexion/bdd.php');

	header("Content-Type:text/html;charset=utf-8");

	$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos_atenc/';
	$nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
	$fichero_subido = $dir_subida . basename($nombre_archivo);
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
		echo "archivo subido";
	}else{
		$nombre_archivo="";
	}

	$sql_e = "UPDATE solicitudes_recursos SET archivo='".$nombre_archivo."' WHERE id='".$_POST['solicitud']."'";

	$query_e = $bdd->prepare( $sql_e );
	if ($query_e == false) {
		print_r($bdd->errorInfo());
		die ('Erreur prepare');
	}
	$sth_e = $query_e->execute();
	if ($sth_e == false) {
		print_r($query_e->errorInfo());
		die ('Erreur execute');
	}

	header("Location: ".$_SERVER['HTTP_REFERER']);

?>