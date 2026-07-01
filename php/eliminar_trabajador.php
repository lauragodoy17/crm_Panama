<?php
require_once("../php/aut.php");
include("../conexion/bdd.php");
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("registrar_historial.php");

$id_usuario_h = intval($_SESSION["id"] ?? 0);
$id = intval($_GET['id']);

$req_old = $bdd->prepare("SELECT id_colegio, nombre, apellido FROM trabajadores_colegios WHERE id=?");
$req_old->execute([$id]);
$old = $req_old->fetch();

$sql = "UPDATE trabajadores_colegios SET activo=0 WHERE id=?";
$req = $bdd->prepare($sql);
$req->execute([$id]);

if ($old) {
    registrar_historial($bdd, $old['id_colegio'], $id_usuario_h, 'Información de contacto',
        'Contacto eliminado', trim($old['nombre'].' '.$old['apellido']), '');
}

header('Location: ../colegio.php?codigo='.$_GET["cod_colegio"].'&periodo='.$_GET["periodo"].'&tab=info_contac&ink_status=ok&ink_msg='.urlencode('Contacto eliminado correctamente.'));
?>
