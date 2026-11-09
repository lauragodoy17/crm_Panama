<?php

	require_once("aut.php");
	require_once('../conexion/bdd.php');

	$id_solicitud = (int)($_POST['solicitud'] ?? 0);
	$volver = '../vista_solicitud.php?id=' . $id_solicitud;

	function volver_con_error($volver, $codigo) {
		header('Location: ' . $volver . '&error_archivo=' . urlencode($codigo));
		exit;
	}

	if ($id_solicitud <= 0) {
		header('Location: ../lista_atenciones.php');
		exit;
	}

	// Si el archivo supera post_max_size, PHP llega con $_FILES vacío.
	if (empty($_FILES['archivo']) || $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE) {
		volver_con_error($volver, empty($_FILES) ? 'tamano' : 'vacio');
	}
	$err = $_FILES['archivo']['error'];
	if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) volver_con_error($volver, 'tamano');
	if ($err !== UPLOAD_ERR_OK) volver_con_error($volver, 'subida');

	$extensiones = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
	$nombre_original = basename($_FILES['archivo']['name']);
	$ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
	if (!in_array($ext, $extensiones, true)) volver_con_error($volver, 'tipo');

	// Nombre seguro: se quitan caracteres raros pero se conserva el nombre legible.
	$nombre_limpio = preg_replace('/[^\p{L}\p{N} ._()-]+/u', '', $nombre_original);
	if ($nombre_limpio === '' || $nombre_limpio === '.' . $ext) $nombre_limpio = 'legalizacion.' . $ext;
	$nombre_archivo = uniqid() . '_' . $nombre_limpio;

	// Ruta relativa al proyecto (no a DOCUMENT_ROOT, que no apunta aquí si el CRM está en una subcarpeta).
	$dir_subida = dirname(__DIR__) . '/adjuntos_atenc/';
	if (!is_dir($dir_subida) && !mkdir($dir_subida, 0775, true)) volver_con_error($volver, 'carpeta');

	if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $dir_subida . $nombre_archivo)) {
		volver_con_error($volver, 'subida');
	}

	$stmt = $bdd->prepare("UPDATE solicitudes_recursos SET archivo = ? WHERE id = ?");
	$stmt->execute([$nombre_archivo, $id_solicitud]);

	header('Location: ' . $volver . '&archivo_ok=1');
	exit;
