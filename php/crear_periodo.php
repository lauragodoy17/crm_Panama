<?php
require_once("aut.php");
require_once("../conexion/bdd.php");

if ($_SESSION["tipo"] != 1) {
    header("Location: ../periodos.php");
    exit;
}

$req_ultimo = $bdd->query("SELECT periodo FROM periodos ORDER BY id DESC LIMIT 1");
$ultimo_periodo = $req_ultimo->fetchColumn();

$anio = $ultimo_periodo !== false ? ((int) preg_replace('/[^0-9]/', '', $ultimo_periodo) + 1) : (int) date('Y');
$periodo = (string) $anio;

$sql = "INSERT INTO periodos (periodo, f_cierre, t_preescolar, t_primaria, t_6_9, t_10_11)
        VALUES (:periodo, :f_cierre, :t_preescolar, :t_primaria, :t_6_9, :t_10_11)";

$req = $bdd->prepare($sql);
$req->execute([
    ':periodo'      => $periodo,
    ':f_cierre'     => $_POST["f_cierre"],
    ':t_preescolar' => $_POST["t_preescolar"],
    ':t_primaria'   => $_POST["t_primaria"],
    ':t_6_9'        => $_POST["t_6_9"],
    ':t_10_11'      => $_POST["t_10_11"],
]);

header("Location: ../periodos.php?ink_status=ok&ink_msg=" . urlencode('Período "' . $periodo . '" creado correctamente.'));
