<?php

	include("../conexion/bdd.php");

	$sql = "UPDATE trabajadores_colegios SET nombre='{$_POST['nombre_profe']}', apellido='{$_POST['apellido_profe']}', telefono='{$_POST['telefono_profe']}', email='{$_POST['correo_profe']}' , area='{$_POST['area_profe']}'  WHERE id='{$_POST['id_profe']}' ";

	$req = $bdd->prepare($sql);
	$req->execute();

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=info_contac');

?>