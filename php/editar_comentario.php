<?php

	if (isset($_POST["comen_edit"])) {
	 
		include("../conexion/bdd.php");

		$sql = "UPDATE visitas SET observaciones='".$_POST["comen_edit"]."' WHERE id_plan_trabajo='".$_POST["id_plan_trabajo"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		
	}


	header('Location: ../evento.php?evento='.$_POST["id_plan_trabajo"].'');
?>
