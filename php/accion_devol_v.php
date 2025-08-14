<?php
	include("../conexion/bdd.php");


	if (isset($_GET["rechazar"])) {

		
		$sql = "UPDATE devoluciones_v SET estado='3' WHERE id='".$_GET["rechazar"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
	}elseif (isset($_GET["aprobar"])) {

	
		$sql = "UPDATE devoluciones_v SET estado='2' WHERE id='".$_GET["aprobar"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		

	}else{

		
		$sql = "UPDATE devoluciones_v SET estado='4', fecha_proceso='".date("Y-m-d H:i:s")."' WHERE id='".$_GET["proceso"]."'";
		
		$req = $bdd->prepare($sql);
		$req->execute();

	}

	header("Location: ".$_SERVER['HTTP_REFERER']);
?>
