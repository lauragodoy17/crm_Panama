<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	if (isset($_POST["colegio"])) {
		require_once("../php/aut.php");
		include("../conexion/bdd.php");

		$sql = "SELECT MAX(id)+1 as cod FROM colegios";
		$req = $bdd->prepare($sql);
		$req->execute();
		$codigo = $req->fetch();

		switch (strlen($codigo["cod"])) {
			case 1: $cod_colegio = "PA0000".$codigo["cod"]; break;
			case 2: $cod_colegio = "PA000".$codigo["cod"];  break;
			case 3: $cod_colegio = "PA00".$codigo["cod"];   break;
			case 4: $cod_colegio = "PA0".$codigo["cod"];    break;
			default: $cod_colegio = "PA".$codigo["cod"];
		}

		$cumple = !empty($_POST["cumple_colegio"]) ? $_POST["cumple_colegio"] : "0000-00-00";

		if ($_SESSION['tipo'] != 6) {

			$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,direccion,telefono,web,cumpleaños,id_usuario,cod_zona) VALUES('".$_POST["colegio"]."', '".$cod_colegio."','".$_POST["departamento"]."','".$_POST["ciudad"]."', '".$_POST["direccion"]."', '".$_POST["telefono"]."', '".$_POST["web"]."', '".$cumple."','".$_SESSION['id']."','".$_POST["cod_zona"]."')";

		}else{

			$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,direccion,telefono,web,cumpleaños,id_usuario,cod_zona,responsable) VALUES('".$_POST["colegio"]."', '".$cod_colegio."','".$_POST["departamento"]."','".$_POST["ciudad"]."', '".$_POST["direccion"]."', '".$_POST["telefono"]."', '".$_POST["web"]."', '".$cumple."','".$_SESSION['id']."','".$_POST["cod_zona"]."','".$_POST["responsable"]."')";
		}

		$req = $bdd->prepare($sql);
		$req->execute();

		echo "<script>alert('Colegio creado correctamente');window.location='../ver_colegios.php'</script>";

	}
?>
