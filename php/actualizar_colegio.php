<?php
	if (isset($_POST["colegio"])) {
	 
		include("../conexion/bdd.php");

		$sql = "UPDATE colegios SET colegio='".$_POST["colegio"]."',departamento='".$_POST["departamento"]."',ciudad='".$_POST["ciudad"]."',dane='".$_POST["dane"]."', direccion='".$_POST["direccion"]."', barrio='".$_POST["barrio"]."', telefono='".$_POST["telefono_c"]."', web='".$_POST["web"]."', correo_i='".$_POST["correo_i"]."', id_calendario='".$_POST["calendario"]."', id_segmento='".$_POST["segmento"]."', responsable='".$_POST["responsable"]."' WHERE codigo='".$_POST["cod_colegio"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();

		$sql = "SELECT id FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

		$req = $bdd->prepare($sql);
		$req->execute();

		$num = $req->rowCount();

		if ($num < 1) {

			$sql_s="INSERT INTO colegios_status (id_colegio, id_periodo, id_status) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["status"]."')";

			$query_s = $bdd->prepare( $sql_s );
			if ($query_s == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth_s = $query_s->execute();
			if ($sth_s == false) {
			 print_r($query_s->errorInfo());
			 die ('Erreur execute');
			}

		}else{

			$sql = "UPDATE colegios_status SET id_status='".$_POST["status"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
			$req = $bdd->prepare($sql);
			$req->execute();

		}

		if ($_SESSION["tipo"]!=6) {


			$sql = "SELECT id FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

			$req = $bdd->prepare($sql);
			$req->execute();

			$num = $req->rowCount();

			if ($num < 1) {

				$sql_s="INSERT INTO colegios_estados_clientes (id_colegio, id_periodo, id_estado_cliente) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["estado_cliente"]."')";

				$query_s = $bdd->prepare( $sql_s );
				if ($query_s == false) {
				 print_r($bdd->errorInfo());
				 die ('Erreur prepare');
				}
				$sth_s = $query_s->execute();
				if ($sth_s == false) {
				 print_r($query_s->errorInfo());
				 die ('Erreur execute');
				}

			}else{

				$sql = "UPDATE colegios_estados_clientes SET id_estado_cliente='".$_POST["estado_cliente"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
				$req = $bdd->prepare($sql);
				$req->execute();

			}

		}

		
	}


	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'');
?>
