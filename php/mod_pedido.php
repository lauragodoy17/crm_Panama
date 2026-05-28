<?php
	
	include("../conexion/bdd.php");

	

	foreach ($_POST["lib_p"] as $lib_p) {
		
		list($cant,$lib,$desc) =explode("/", $lib_p);

		$sql_e = "UPDATE libros_pedidos SET cantidad_aprob='".$cant."', descuento_aprob='".$desc."' WHERE id='".$lib."'";

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

	$sql_e = "UPDATE pedidos SET observaciones='".$_POST["observaciones"]."' WHERE id='".$_POST["pedido"]."'";

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

	
	$tp = $_POST['tp'] ?? '';
	if ($_POST['salida']=="aprobado") {
		header('Location: ../pedido_colegio.php?id_pedido='.$_POST["pedido"].'&tp='.$tp);
	}else{
		header('Location: ../pedido_colegio.php?id_pedido='.$_POST["pedido"].'&tp='.$tp);
	}

	

?>