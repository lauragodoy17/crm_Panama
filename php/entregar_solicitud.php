<?php

	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/

	require_once("aut.php"); 
	require_once('../conexion/bdd.php');

		
	foreach ($_POST["i_entrega"] as $entregas => $entrega) {

		list($recurso, $v_tipo_e, $v_valor_e, $v_fecha_e, $v_legal) = explode("|", $entrega);

		if ($recurso !=0) {
				
			$sql_p = "UPDATE recursos_solicitados SET tipo_e='".$v_tipo_e."', valor_e='".$v_valor_e."', fecha_e='".$v_fecha_e."' WHERE id='".$recurso."' ";
					
					
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


	$sql = "UPDATE solicitudes_recursos SET estado='4' WHERE id='".$_POST["solicitud"]."'";
	$req = $bdd->prepare($sql);
	$req->execute();
	

	header('Location: ../vista_solicitud.php?id='.$_POST["solicitud"].'&updated=1');
	
?>