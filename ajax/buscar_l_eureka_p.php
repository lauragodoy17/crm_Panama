<?php
	require_once('../conexion/bdd.php');
	list($materia, $grado) = explode("/", $_POST["mat_gra"]);
	$sql = "SELECT id,libro FROM libros WHERE id_materia='".$materia."' AND id_grado='".$grado."' AND etiqueta !='MUESTRA' AND presupuesto='1' ORDER BY libro";
	$req = $bdd->prepare($sql);
	$req->execute();
	$libros = $req->fetchAll();
	echo"<option value=''>Seleccione</option>";
	foreach($libros as $lib) {;
		echo"<option value=".$lib["id"].">".$lib["libro"]."</option>";
	}
?>