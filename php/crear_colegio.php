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

		if ($_SESSION['tipo'] == 1) {

			if ($_POST["empresa"] == "1") {
				$cod_zona    = $_POST["zona_asignada"];
				$sub_zona    = 0;
				$responsable = "";
			} else {
				$cod_zona    = $_POST["empresa"];
				$sub_zona    = $_POST["zona_asignada"];
				$responsable = $_POST["responsable_admin"];
			}

			$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,direccion,telefono,web,cumpleaños,id_usuario,cod_zona,sub_zona,responsable)
					VALUES(:colegio,:codigo,:departamento,:ciudad,:direccion,:telefono,:web,:cumple,:id_usuario,:cod_zona,:sub_zona,:responsable)";
			$req = $bdd->prepare($sql);
			$req->execute([
				':colegio'      => $_POST["colegio"],
				':codigo'       => $cod_colegio,
				':departamento' => $_POST["departamento"],
				':ciudad'       => $_POST["ciudad"],
				':direccion'    => $_POST["direccion"],
				':telefono'     => $_POST["telefono"],
				':web'          => $_POST["web"],
				':cumple'       => $cumple,
				':id_usuario'   => $_SESSION['id'],
				':cod_zona'     => $cod_zona,
				':sub_zona'     => $sub_zona,
				':responsable'  => $responsable,
			]);

		} elseif ($_SESSION['tipo'] != 6) {

			$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,direccion,telefono,web,cumpleaños,id_usuario,cod_zona) VALUES('".$_POST["colegio"]."', '".$cod_colegio."','".$_POST["departamento"]."','".$_POST["ciudad"]."', '".$_POST["direccion"]."', '".$_POST["telefono"]."', '".$_POST["web"]."', '".$cumple."','".$_SESSION['id']."','".$_POST["cod_zona"]."')";
			$req = $bdd->prepare($sql);
			$req->execute();

		}else{

			$sql = "INSERT INTO colegios(colegio,codigo,departamento,ciudad,direccion,telefono,web,cumpleaños,id_usuario,cod_zona,responsable) VALUES('".$_POST["colegio"]."', '".$cod_colegio."','".$_POST["departamento"]."','".$_POST["ciudad"]."', '".$_POST["direccion"]."', '".$_POST["telefono"]."', '".$_POST["web"]."', '".$cumple."','".$_SESSION['id']."','".$_POST["cod_zona"]."','".$_POST["responsable"]."')";
			$req = $bdd->prepare($sql);
			$req->execute();
		}

		echo "<script>alert('Colegio creado correctamente');window.location='../ver_colegios.php'</script>";

	}
?>
