<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	include("../conexion/bdd.php");

	$sql = "SELECT adjunto FROM adjuntos WHERE id='".$_GET['id_ad']."'";
    $req = $bdd->prepare($sql);
    $req->execute();
	$adjunto = $req->fetch();

	$sql_e = "DELETE FROM adjuntos WHERE id='".$_GET['id_ad']."'";
    $req_e = $bdd->prepare($sql_e);
    $req_e->execute();
	$eliminar = $req_e->fetch();

	$dir_subida = '../adjuntos/';
	$fichero_subido=$dir_subida . basename ($adjunto['adjunto']);

	If (unlink('../adjuntos/'.$adjunto['adjunto'].'')) {
  		// file was successfully deleted
	} else {
  		// there was a problem deleting the file
	}

	header('Location: ../colegio.php?codigo='.$_GET["cod_colegio"].'&periodo='.$_GET["periodo"].'&tab=adjuntos');


?>