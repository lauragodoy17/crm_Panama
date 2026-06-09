<?php
	ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	header("Content-Type:text/html;charset=utf-8");	
	
	$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos/envio/';
	$nombre_archivo=uniqid()."_".$_FILES['adjunto_envio']['name'];
	$fichero_subido = $dir_subida . basename($nombre_archivo);
	if (move_uploaded_file($_FILES['adjunto_envio']['tmp_name'], $fichero_subido)) {
		echo "archivo subido";
	}else{
		$nombre_archivo="";
	}




		$sql_z = "UPDATE ordenes_pedidos SET tipo_doc='".$_POST["tipo_doc"]."', n_doc='".$_POST["n_doc"]."', cliente='".$_POST["cliente"]."', estado='2'  WHERE id='".$_POST["op"]."' ";

		
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

		$sql_z = "INSERT INTO op_atendidas(opid,n_doc,transportista,guia,fecha_entrega,valor,obs_envio,fecha_at,usuario_at,adjunto_envio) VALUES('".$_POST["op"]."', '".$_POST["n_doc"]."','".$_POST["transportista"]."','".$_POST["guia"]."','".$_POST["fecha_entrega"]."','".$_POST["valor"]."', '".$_POST["obs_envio"]."', '".date("Y-m-d H:i:s")."', '".$_SESSION['id']."','".$nombre_archivo."')";

		
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


	

	header('Location: ../formato_op.php?op='.$_POST["op"].'&ink_status=ok&ink_msg='.urlencode('OP atendida correctamente.'));
?>
