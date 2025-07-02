<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	foreach ($_POST['b_presup'] as $presup) {

		$sql_eliminar="DELETE FROM presupuestos WHERE id='".$presup."'";

		$req_eliminar = $bdd->prepare($sql_eliminar);
		$req_eliminar->execute();
	}
		

	header('Location: ../colegio.php?codigo='.$_POST["codigo"].'&periodo='.$_POST["periodo"].'&tab=presupuesto');
?>
