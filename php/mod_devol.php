<?php
	
	include("../conexion/bdd.php");

	$sql = "SELECT id FROM libros_devol WHERE cod_pedido='".$_POST['codigo']."'";

	$req = $bdd->prepare($sql);
	$req->execute();
	$libs= $req->fetchAll();

	foreach($libs as $lib) {

		$libsp[]=$lib["id"];
	}

	$resultados = array_diff($libsp, $_POST['lpid']);

	foreach($resultados as $resultado) {

		$sql = "DELETE FROM `libros_devol` WHERE id='".$resultado."'";

		$req = $bdd->prepare($sql);
		$req->execute();

	}

	foreach ($_POST["libro_e"] as $libros => $libro) {

		list($id_libro,$cantidad) = array_pad(explode("/", $libro), 2, '');
			
		if ($id_libro !='') {
			
			$sql_p = "INSERT INTO libros_devol(cod_pedido,id_libro,cantidad) VALUES('".$_POST['codigo']."','".$id_libro."','".$cantidad."')";
				
				
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
		
		list($cant,$lib,$desc) =explode("/", $lib_p);

		$sql_e = "UPDATE libros_devol SET cantidad='".$cant."' WHERE id='".$lib."'";

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

	if ($_POST["tipo"]==1) {
		$sql_e = "UPDATE devoluciones SET persona='".$_POST['persona']."', observaciones='".$_POST['observaciones']."' WHERE codigo='".$_POST['codigo']."'";
	}else{
		$sql_e = "UPDATE devoluciones_prov SET persona='".$_POST['persona']."', observaciones='".$_POST['observaciones']."' WHERE codigo='".$_POST['codigo']."'";
	}
	

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

	

	header('Location: ../vista_devol.php?id_devol='.$_POST["pedido"].'&tipo='.$_POST["tipo"].'');


	

?>