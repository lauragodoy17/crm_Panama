<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php'); 

	$sql = "SELECT id,libro, precio FROM libros WHERE id_materia='".$_POST["mat_gra"]."' AND id_grado!=50 AND id_grado!=51 AND id_grado!=15 AND id_grado!=16 AND presupuesto=1 ORDER BY libro";
	$req = $bdd->prepare($sql);
	$req->execute();
	$libros = $req->fetchAll();
	echo"<option value=''>Seleccione</option>";
	foreach($libros as $lib) {;
		echo"<option value=".$lib["id"].">".$lib["libro"]." - $".$lib["precio"]."</option>";
	}
?>