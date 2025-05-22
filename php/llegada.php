<?php
require_once("aut.php");
// Conexion a la base de datos
require_once('../conexion/bdd.php');

	$sql_periodo="SELECT id FROM periodos ORDER BY id DESC";

	$req_periodo = $bdd->prepare($sql_periodo);
	$req_periodo->execute();
	$gp_periodo = $req_periodo->fetch();

	//$color = $_POST['color'];

	
		$sql_v = "INSERT INTO visitas(id_periodo,id_plan_trabajo,fecha_llegada,latitud,longitud) values ('".$gp_periodo["id"]."','".$_POST["id_visita"]."','".date("Y-m-d H:i:s")."','".$_POST["latitud1"]."', '".$_POST["longitud1"]."')";
		
		
		$query_v = $bdd->prepare( $sql_v );
		if ($query_v == false) {
		 print_r($bdd->errorInfo());
		 die ('Erreur prepare');
		}
		$sth_v = $query_v->execute();
		if ($sth_v == false) {
		 print_r($query_v->errorInfo());
		 die ('Erreur execute');
		}



header('Location: ../evento.php?evento='.$_POST["id_visita"].'');

	
?>
