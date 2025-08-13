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

	$sql = "UPDATE pedidos SET estado='2', observaciones='".$_POST["observaciones"]."' WHERE id='".$_POST["pedido"]."'";
	$req = $bdd->prepare($sql);
	$req->execute();

	header('Location: ../lista_pedidos.php?tp=2');

?>