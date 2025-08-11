<?php
	if (isset($_POST["colegio"])) {
	 
		include("../conexion/bdd.php");
		header("Content-Type:text/html;charset=utf-8");	

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

		if ($_POST['propuesta_c']==1) {

			$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos/';
			$nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
			$fichero_subido = $dir_subida . basename($nombre_archivo);
			if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
				echo "archivo subido";
			}else{
				$nombre_archivo="";
			}

			$sql_z = "INSERT INTO adjuntos (id_colegio,id_periodo,tipo,adjunto) VALUES ('".$_POST["id_colegio"]."','".$_POST["periodo"]."','1','".$nombre_archivo."')";

			$query_z = $bdd->prepare( $sql_z );
			if ($query_z == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$sth_z = $query_z->execute();
			if ($sth_z == false) {
			 print_r($query_z->errorInfo());
			 die ('Erreur execute');
			}
			
		}else{

			$sql = "DELETE FROM adjuntos WHERE id_colegio = '".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
			$query = $bdd->prepare( $sql );
			if ($query == false) {
			 print_r($bdd->errorInfo());
			 die ('Erreur prepare');
			}
			$res = $query->execute();
			if ($res == false) {
			 print_r($query->errorInfo());
			 die ('Erreur execute');
			}
		}
		
		
	}


	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'');
?>
