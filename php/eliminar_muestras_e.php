<?php
	
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	$sql_e = "UPDATE muestreos_e SET estado=0 WHERE id='".$_GET['codigo']."'";

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

	header('Location: ../muestras_entregadas.php?ink_status=ok&ink_msg='.urlencode('Muestra eliminada correctamente.'));

?>