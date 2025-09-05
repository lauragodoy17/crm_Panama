<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	print_r($_POST["entrega1"]);
		
	foreach ($_POST["entrega1"] as $lib_p) {

		list($libro,$cantidad) = explode("/", $lib_p);

		if ($libro !="") {

			$sql_p = "INSERT INTO entregas_opd(id_libro_opd,cant_entregada,observacion_entrega) VALUES('".$libro."','".$cantidad."','".$_POST["observaciones_ent"]."')";
				
				
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

	foreach ($_POST["entrega2"] as $lib_p) {

		list($libro,$cantidad) = explode("/", $lib_p);

		if ($libro !="") {

			$sql_p = "INSERT INTO entregas_opd(id_libro_opd,cant_entregada,observacion_entrega) VALUES('".$libro."','".$cantidad."','".$_POST["observaciones_ent"]."')";
				
				
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

	foreach ($_POST["entrega3"] as $lib_p) {

		list($libro,$cantidad) = explode("/", $lib_p);

		if ($libro !="") {

			$sql_p = "INSERT INTO entregas_opd(id_libro_opd,cant_entregada,observacion_entrega) VALUES('".$libro."','".$cantidad."','".$_POST["observaciones_ent"]."')";
				
				
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

	foreach ($_POST["entrega4"] as $lib_p) {

		list($libro,$cantidad) = explode("/", $lib_p);

		if ($libro !="") {

			$sql_p = "INSERT INTO entregas_opd(id_libro_opd,cant_entregada,observacion_entrega) VALUES('".$libro."','".$cantidad."','".$_POST["observaciones_ent"]."')";
				
				
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

	foreach ($_POST["entrega5"] as $lib_p) {

		list($libro,$cantidad) = explode("/", $lib_p);

		if ($libro !="") {

			$sql_p = "INSERT INTO entregas_opd(id_libro_opd,cant_entregada,observacion_entrega) VALUES('".$libro."','".$cantidad."','".$_POST["observaciones_ent"]."')";
				
				
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


header("Location: ../opd_solicitada.php?opd=".$_POST['opd']."");
	

?>