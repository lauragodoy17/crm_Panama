<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

	if ($_POST["empresa"]=="1") {
		$sql = "SELECT z.codigo, z.zona, CONCAT(u.nombres, ' ', u.apellidos) as promotor FROM zonas z JOIN usuarios u ON u.cod_zona=z.codigo WHERE z.zona LIKE '%Eureka%' AND u.act=1";
		$req = $bdd->prepare($sql);
		$req->execute();
		$zonas = $req->fetchAll();
		echo"<option value=''>Seleccione</option>";
		echo"<option value='356587'>EMPRESA</option>";
		foreach($zonas as $zona) {
			list($emp,$n_zona)=explode("/", $zona["zona"]);
			echo"<option value=".$zona["codigo"].">".$n_zona." ( ".$zona["promotor"]." )</option>";
		}

	}else{
		$sql = "SELECT id, sub_zona FROM sub_zonas WHERE cod_zona='".$_POST["empresa"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		$zonas = $req->fetchAll();

		echo"<option value=''>Seleccione</option>";
		foreach($zonas as $zona) {
			echo"<option value=".$zona["id"].">".$zona["sub_zona"]."</option>";
		}
	}


?>