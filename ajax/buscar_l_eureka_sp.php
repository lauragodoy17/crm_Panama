<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php'); 

	$sql = "SELECT id,libro,id_grado FROM libros WHERE id_materia='".$_POST["mat_gra"]."' AND id_grado!=50 AND id_grado!=51 AND presupuesto=1 ORDER BY
  CASE
    WHEN libro LIKE '%Primaria%' THEN 1
    WHEN libro LIKE '%Bachillerato%' THEN 2
    ELSE 3
  END,
  libro;";
	$req = $bdd->prepare($sql);
	$req->execute();
	$libros = $req->fetchAll();
	echo"<option value=''>Seleccione</option>";
	foreach($libros as $lib) {;
		echo"<option value=".$lib["id"]." data-grado=".$lib["id_grado"].">".$lib["libro"]."</option>";
	}
?>