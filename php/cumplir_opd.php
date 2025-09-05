<?php
	
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	$sql_e = "UPDATE ordenes_produccion SET estado='4' WHERE id='".$_POST['opd']."'";

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

	header("Location: ../opd_solicitada.php?opd=".$_POST['opd']."");
?>