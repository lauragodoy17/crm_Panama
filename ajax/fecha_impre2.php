<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

	list($fecha_impre, $pedidoid) = explode("/", $_POST["feid"]);

	$sql_p = "UPDATE pedidos2 SET fecha_impre='".$fecha_impre."' WHERE id='".$pedidoid."' ";
					
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
?>

