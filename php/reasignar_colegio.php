<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	if ($_POST["empresa"]!=1) {

		$sql="UPDATE colegios SET cod_zona='".$_POST["empresa"]."', sub_zona='".$_POST["zona"]."', responsable='".$_POST["responsable"]."' WHERE id='".$_POST["id_colegio"]."'";
			
	}else{

		$sql="UPDATE colegios SET cod_zona='".$_POST["zona"]."', sub_zona='0', responsable='' WHERE id='".$_POST["id_colegio"]."'";

	}

	$req = $bdd->prepare($sql);
	$req->execute();

	echo "<script>alert('Colegio reasignado');window.location='../colegio.php?codigo=".$_POST["cod_colegio"]."'</script>";



?>