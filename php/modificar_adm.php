<?php

	include("../conexion/bdd.php");

	$sql = "UPDATE trabajadores_colegios SET nombre='{$_POST['nombre_adm']}', apellido='{$_POST['apellido_adm']}', telefono='{$_POST['telefono_adm']}', email='{$_POST['correo_adm']}' , cargo='{$_POST['cargo_adm']}'  WHERE id='{$_POST['id_adm']}' ";

	$req = $bdd->prepare($sql);
	$req->execute();

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=info_contac');

?>