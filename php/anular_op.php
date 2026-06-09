<?php 

	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	$sql_z = "UPDATE ordenes_pedidos SET estado=4, motivo_anu='".$_POST["motivo_anu"]."', fecha_anu='".date("Y-m-d H:i:s")."', usuario_anu='".$_SESSION['id']."' WHERE id='".$_POST["op"]."'";

	$query_z = $bdd->prepare( $sql_z );
	if ($query_z == false) {
		print_r($bdd->errorInfo());
		die ('Erreur prepare');
	}
	$sth_z = $query_z->execute();
	if ($sth_z == false) {
		print_r($query_z->errorInfo());
		die ('Erreur execute');
	}

	header("location: ../lista_op.php?tp=2&ink_status=ok&ink_msg=".urlencode('OP anulada correctamente.'));
 ?>