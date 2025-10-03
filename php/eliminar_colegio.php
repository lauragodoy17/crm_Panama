<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	$sql_eliminar="DELETE  FROM colegios WHERE codigo='".$_GET["codigo"]."'";

	$req_eliminar = $bdd->prepare($sql_eliminar);
	$req_eliminar->execute();

?>
<script>alert('Colegio eliminado');window.location="../ver_colegios.php";
</script>;