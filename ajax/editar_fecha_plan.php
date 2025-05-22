<?php

// Conexion a la base de datos
require_once('../conexion/bdd.php');

if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	
	
	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];

	$sql = "SELECT start, resultado FROM plan_trabajo WHERE id='".$id."'";

	$req = $bdd->prepare($sql);
	$req->execute();
	$plan = $req->fetch();

	if ($plan["resultado"] == 0) {
		
		$sql = "UPDATE plan_trabajo SET  start = '$start', end = '$end' WHERE id = $id ";

	
		$query = $bdd->prepare( $sql );
		if ($query == false) {
		 print_r($bdd->errorInfo());
		 die ('Error');
		}
		$sth = $query->execute();
		if ($sth == false) {
		 print_r($query->errorInfo());
		 die ('Error');
		}else{
			die ('OK');
		}

	}else{
		echo "Error";
	}

	

}
//header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
