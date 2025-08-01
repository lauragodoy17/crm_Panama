<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

		header("Content-Type:text/html;charset=utf-8");	
	
		$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos/';
		$nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
		$fichero_subido = $dir_subida . basename($nombre_archivo);
		if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
			echo "archivo subido";
		}else{
			$nombre_archivo="";
		}

		if (!isset($_POST['pedidos_agp'])) {
			
			$sql_z = "INSERT INTO ordenes_pedidos (usuario,op_per,tipo_doc,cliente,solicitante,ciudad_destino,observaciones,archivo, estado, id_pedido, id_pedido_dist, id_muestreo, id_devol_c, id_devol_p, id_devol_v, año) VALUES ('".$_SESSION['id']."','".$_POST['op_per']."','".$_POST["tipo_doc"]."', '".$_POST["cliente"]."', '".$_POST["solicitante"]."', '".$_POST["ciudad_d"]."', '".$_POST["observaciones"]."','".$nombre_archivo."','1', '".$_POST["id_pedido"]."', '".$_POST["id_pedido_dist"]."', '".$_POST["id_muestreo"]."','".$_POST["id_devol_c"]."','".$_POST["id_devol_p"]."','".$_POST["id_devol_v"]."', '".date("Y")."')";

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

			$sql_z = "INSERT INTO ordenes_pedidos (usuario,op_per,tipo_doc,cliente,solicitante,ciudad_destino,observaciones,archivo, estado, año) VALUES ('".$_SESSION['id']."','".$_POST['op_per']."','".$_POST["tipo_doc"]."', '".$_POST["cliente"]."', '".$_POST["solicitante"]."', '".$_POST["ciudad_d"]."', '".$_POST["observaciones"]."','".$nombre_archivo."','1', '".date("Y")."')";

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

			$sql_op = "SELECT MAX(id) as ult FROM ordenes_pedidos";

			$req_op = $bdd->prepare($sql_op);
			$req_op->execute();
			$op = $req_op->fetch();

			foreach ($_POST['pedidos_agp'] as $pedido_agp) {

				$sql_op = "INSERT INTO op_pedidos_agrupados (tipo,op,id_pedido) VALUES ('1','".$op["ult"]."', '".$pedido_agp."')";

				$query_op = $bdd->prepare( $sql_op );
				if ($query_op == false) {
				 print_r($bdd->errorInfo());
				 die ('Erreur prepare');
				}
				$sth_op = $query_op->execute();
				if ($sth_op == false) {
				 print_r($query_op->errorInfo());
				 die ('Erreur execute');
				}

			}
			

		}
						

		$sql = "SELECT MAX(id) as ult FROM ordenes_pedidos";

		$req = $bdd->prepare($sql);
		$req->execute();

		$op = $req->fetch();

		header('Location: ../formato_op.php?op='.$op["ult"].'');

?>
