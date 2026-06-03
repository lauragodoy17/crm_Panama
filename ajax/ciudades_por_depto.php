<?php
require_once("../php/aut.php");
require_once('../conexion/bdd.php');

$depto_id = isset($_POST['departamento']) ? intval($_POST['departamento']) : 0;

if ($depto_id <= 0) exit;

// Si existe la tabla municipios usar nombres oficiales,
// solo mostrando los que tienen al menos un colegio en ese departamento
$usa_municipios = false;
try { $bdd->query("SELECT 1 FROM municipios LIMIT 1"); $usa_municipios = true; } catch (Exception $e) {}

if ($usa_municipios) {
    $stmt = $bdd->prepare("
        SELECT DISTINCT m.nombre AS ciudad
        FROM municipios m
        INNER JOIN colegios c ON c.departamento = m.id_departamento AND c.ciudad = m.nombre
        WHERE m.id_departamento = ?
        UNION
        SELECT DISTINCT c.ciudad
        FROM colegios c
        WHERE c.departamento = ?
          AND c.ciudad != '' AND c.ciudad IS NOT NULL
          AND NOT EXISTS (
              SELECT 1 FROM municipios m
              WHERE m.id_departamento = c.departamento AND m.nombre = c.ciudad
          )
        ORDER BY ciudad
    ");
    $stmt->execute([$depto_id, $depto_id]);
} else {
    $stmt = $bdd->prepare("
        SELECT DISTINCT ciudad
        FROM colegios
        WHERE departamento = ? AND ciudad != '' AND ciudad IS NOT NULL
        ORDER BY ciudad
    ");
    $stmt->execute([$depto_id]);
}

$ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ciudades as $row) {
    $c = htmlspecialchars($row['ciudad']);
    echo '<option value="' . $c . '">' . $c . '</option>';
}
