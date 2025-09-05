<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");


	$sql = "SELECT id FROM libros_opd WHERE opid='".$_POST['opd']."'";

	$req = $bdd->prepare($sql);
	$req->execute();
	$libs= $req->fetchAll();

	foreach($libs as $lib) {

		$libsp[]=$lib["id"];
	}

	$resultados = array_diff($libsp, $_POST['lpid']);


	foreach($resultados as $resultado) {

		$sql = "DELETE FROM libros_opd WHERE id='".$resultado."'";

		$req = $bdd->prepare($sql);
		$req->execute();

	}

	foreach ($_POST["libro_e"] as $libros => $libro) {

		list($libro,$cantidad,$enca) = explode("/", $libro);
			
		if ($libro !="") {
			
			$sql_p = "INSERT INTO libros_opd(opid,libro,encaratulado,cantidad) VALUES('".$_POST['opd']."','".$libro."','".$enca."','".$cantidad."')";
				
				
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

	foreach ($_POST["lib_p"] as $lib_p) {
		
		list($lib,$cant) =explode("/", $lib_p);

		$sql_e = "UPDATE libros_opd SET cantidad='".$cant."' WHERE id='".$lib."'";

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

	}
	
	$sql_e = "UPDATE ordenes_produccion SET observaciones='".$_POST["observaciones"]."', cliente='".$_POST["persona"]."', orden_pedido='".$_POST["orden_pedido"]."',fecha_ent_s='".$_POST["fecha_ent_s"]."' WHERE id='".$_POST["opd"]."'";

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