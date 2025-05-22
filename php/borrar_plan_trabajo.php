<?php
// Conexion a la base de datos
require_once('../conexion/bdd.php');
if (isset($_GET['evento'])){
	
	
	$evento = $_GET['evento'];
	
	$sql = "DELETE FROM plan_trabajo WHERE id = $evento";
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$res = $query->execute();
	if ($res == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}
	
}
header('Location: ../agenda.php');

	
?>