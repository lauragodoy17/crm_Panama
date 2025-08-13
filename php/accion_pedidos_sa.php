<?php
	include("../conexion/bdd.php");


	if (isset($_GET["rechazar"])) {
		
		$sql = "UPDATE pedidos2 SET estado='3' WHERE id='".$_GET["rechazar"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		echo "<script>alert('Pedido Rechazado');window.location='../lista_pedidos_sa.php?tp=2';</script>";
	}elseif (isset($_GET["aprobar"])) {

		$sql = "UPDATE pedidos2 SET estado='2' WHERE id='".$_GET["aprobar"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		echo "<script>alert('Pedido Aprobado');window.location='../lista_pedidos_sa.php?tp=2';</script>";

	}else{

		$sql = "UPDATE pedidos2 SET estado='4' WHERE id='".$_GET["entregado"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		echo "<script>alert('Pedido Entregado');window.location='../lista_pedidos_sa.php?tp=3';</script>";
	}

	
?>
