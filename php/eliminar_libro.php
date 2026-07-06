<?php
require_once("aut.php");
require_once("../conexion/bdd.php");

if ($_SESSION["tipo"] != 1 || !isset($_POST["id_libro"])) {
    header("Location: ../libros.php");
    exit;
}

$id_libro = $_POST["id_libro"];

$req_uso = $bdd->prepare("SELECT COUNT(*) as total FROM presupuestos WHERE id_libro=:id");
$req_uso->execute([':id' => $id_libro]);
$uso_presupuesto = $req_uso->fetch()["total"];

$req_serie = $bdd->prepare("SELECT COUNT(*) as total FROM libros WHERE pri_sec=:id");
$req_serie->execute([':id' => $id_libro]);
$uso_serie = $req_serie->fetch()["total"];

if ($uso_presupuesto > 0 || $uso_serie > 0) {
    $msg = urlencode('No se puede eliminar: el libro ya está referenciado en presupuestos u otros registros.');
    header("Location: ../libros.php?ink_status=error&ink_msg=" . $msg);
    exit;
}

$req_del = $bdd->prepare("DELETE FROM libros WHERE id=:id");
$req_del->execute([':id' => $id_libro]);

header("Location: ../libros.php?ink_status=ok&ink_msg=" . urlencode('Libro eliminado correctamente.'));
