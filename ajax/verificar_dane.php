<?php
require_once("../php/aut.php");
require_once('../conexion/bdd.php');

$dane = isset($_POST['dane']) ? trim($_POST['dane']) : '';

if (!preg_match('/^\d{12}$/', $dane)) {
    echo json_encode(['disponible' => false]);
    exit;
}

$stmt = $bdd->prepare("SELECT id FROM colegios WHERE dane = ? LIMIT 1");
$stmt->execute([$dane]);

echo json_encode(['disponible' => $stmt->rowCount() === 0]);
