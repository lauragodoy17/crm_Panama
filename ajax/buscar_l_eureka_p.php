<?php
	require_once('../conexion/bdd.php');
	list($materia, $grado) = array_pad(explode("/", $_POST["mat_gra"]), 2, '');

	// Inglés (7) y Español (3, incluye Plan Lector) permiten buscar el libro
	// por nombre antes de elegir el curso: el mismo título puede terminar
	// asignado a un curso distinto según el colegio, así que el curso no
	// se toma del libro (se marca data-libre para asignarlo aparte).
	$materias_busqueda_libre = [3, 7];

	if ($grado === '' && in_array((int)$materia, $materias_busqueda_libre, true)) {
		$sql = "SELECT id, libro FROM libros WHERE id_materia = :materia AND etiqueta != 'MUESTRA' AND presupuesto = '1' ORDER BY libro";
		$req = $bdd->prepare($sql);
		$req->execute([':materia' => $materia]);
		$libros = $req->fetchAll();

		echo "<option value=''>Seleccione</option>";
		foreach ($libros as $lib) {
			echo '<option value="'.$lib["id"].'" data-libre="1">'.htmlspecialchars($lib["libro"]).'</option>';
		}
		return;
	}

	if ($grado === '') return;

	$sql = "SELECT id, libro FROM libros WHERE id_materia = :materia AND id_grado = :grado AND etiqueta != 'MUESTRA' AND presupuesto = '1' ORDER BY libro";
	$req = $bdd->prepare($sql);
	$req->execute([':materia' => $materia, ':grado' => $grado]);
	$libros = $req->fetchAll();

	echo "<option value=''>Seleccione</option>";
	foreach ($libros as $lib) {
		echo '<option value="'.$lib["id"].'">'.htmlspecialchars($lib["libro"]).'</option>';
	}
?>