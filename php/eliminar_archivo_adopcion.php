<?php

	require_once("../php/aut.php");
	include("../conexion/bdd.php");
	require_once("registrar_historial.php");

	$id_usuario_h = intval($_SESSION["id"] ?? 0);

	$sql = "SELECT archivo FROM recursos_archivos WHERE id='".intval($_GET['id_archivo'])."'";
	$req = $bdd->prepare($sql);
	$req->execute();
	$archivo = $req->fetch();

	if ($archivo) {
		$sql_e = "DELETE FROM recursos_archivos WHERE id='".intval($_GET['id_archivo'])."'";
		$req_e = $bdd->prepare($sql_e);
		$req_e->execute();

		$ruta_fisica = dirname(__DIR__) . '/' . $archivo['archivo'];
		if (is_file($ruta_fisica)) {
			unlink($ruta_fisica);
		}

		registrar_historial($bdd, $_GET["colegio"], $id_usuario_h, 'Adopciones',
			'Acuerdo de adopción', basename($archivo['archivo']), '');
	}

	header('Location: ../colegio.php?codigo='.$_GET["codigo"].'&periodo='.$_GET["periodo"].'&tab=adopciones');

?>
