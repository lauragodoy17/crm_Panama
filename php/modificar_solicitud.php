<?php

	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/

	require_once("aut.php"); 
	require_once('../conexion/bdd.php');

	if ($_GET['tipo']==1) {
		
		foreach ($_POST["i_presup"] as $legalizas => $legaliza) {

			list($recurso, $v_legal) = explode("/", $legaliza);

			if ($recurso !=0) {
				
				$sql_p = "UPDATE recursos_solicitados SET presupuesto='".$v_legal."' WHERE id='".$recurso."' ";
					
					
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

	}else{

		foreach ($_POST["i_legaliza"] as $legalizas => $legaliza) {

			list($recurso, $v_legal) = explode("|", $legaliza);

			if ($recurso !=0) {
				
				$sql_p = "UPDATE recursos_solicitados SET legaliza='".$v_legal."' WHERE id='".$recurso."'";
					
					
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

	}

	

	header('Location: ../vista_solicitud.php?id='.$_POST["solicitud"].'');
	
?>