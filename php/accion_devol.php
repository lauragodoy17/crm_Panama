<?php
	include("../conexion/bdd.php");


	if (isset($_GET["rechazar"])) {

		if ($_GET["tipo"]==1) {
			$sql = "UPDATE devoluciones SET estado='3' WHERE id='".$_GET["rechazar"]."'";
		}else{
			$sql = "UPDATE devoluciones_prov SET estado='3' WHERE id='".$_GET["rechazar"]."'";
		}
		
		$req = $bdd->prepare($sql);
		$req->execute();
	}elseif (isset($_GET["aprobar"])) {

		if ($_GET["tipo"]==1) {
			$sql = "UPDATE devoluciones SET estado='2' WHERE id='".$_GET["aprobar"]."'";
		}else{
			$sql = "UPDATE devoluciones_prov SET estado='2' WHERE id='".$_GET["aprobar"]."'";
		}
		
		$req = $bdd->prepare($sql);
		$req->execute();
		

	}else{

		if ($_GET["tipo"]==1) {
			$sql = "UPDATE devoluciones SET estado='4', fecha_proceso='".date("Y-m-d H:i:s")."' WHERE id='".$_GET["proceso"]."'";
		}else{
			$sql = "UPDATE devoluciones_prov SET estado='4', fecha_proceso='".date("Y-m-d H:i:s")."'   WHERE id='".$_GET["proceso"]."'";
		}
		
		$req = $bdd->prepare($sql);
		$req->execute();

	}

	header("Location: ".$_SERVER['HTTP_REFERER']);
?>
