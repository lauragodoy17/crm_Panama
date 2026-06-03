<?php
require_once("../php/aut.php");
require_once('../conexion/bdd.php');

$depto_id = isset($_POST['departamento']) ? intval($_POST['departamento']) : 0;

echo '<option value="">Seleccione una ciudad</option>';

if ($depto_id > 0) {
    // Usar tabla municipios si ya fue importada; si no, usar ciudades existentes en colegios
    $usar_municipios = false;
    try {
        $bdd->query("SELECT 1 FROM municipios LIMIT 1");
        $usar_municipios = true;
    } catch (Exception $e) {}

    if ($usar_municipios) {
        $stmt = $bdd->prepare("
            SELECT nombre AS ciudad
            FROM municipios
            WHERE id_departamento = ?
            ORDER BY nombre
        ");
    } else {
        $stmt = $bdd->prepare("
            SELECT DISTINCT ciudad
            FROM colegios
            WHERE departamento = ? AND ciudad != '' AND ciudad IS NOT NULL
            ORDER BY ciudad
        ");
    }

    $stmt->execute([$depto_id]);
    $ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ciudades as $row) {
        $c = htmlspecialchars($row['ciudad']);
        echo '<option value="' . $c . '">' . $c . '</option>';
    }
}

echo '<option value="__otra__">Otra ciudad...</option>';
