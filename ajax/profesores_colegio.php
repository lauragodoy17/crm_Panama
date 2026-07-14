<?php
require_once("../php/aut.php");
include("../conexion/bdd.php");
header('Content-Type: application/json; charset=utf-8');

$id_colegio = intval($_GET['id_colegio'] ?? 0);

$profesores = [];
if ($id_colegio > 0) {
    $stmt = $bdd->prepare("
        SELECT tc.id,
               CONCAT(tc.nombre, ' ', tc.apellido,
                      IF(tc.cargo = 6, '', CONCAT(' (', COALESCE(c.cargo, 'Directivo'), ')'))) AS nombre
        FROM trabajadores_colegios tc
        LEFT JOIN cargos c ON c.id = tc.cargo
        WHERE tc.id_colegio = :id_colegio AND tc.activo = 1
        ORDER BY (tc.cargo = 6) DESC, tc.nombre, tc.apellido
    ");
    $stmt->execute([':id_colegio' => $id_colegio]);
    $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($profesores);
