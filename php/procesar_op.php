<?php
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

	$sql_z = "UPDATE ordenes_pedidos SET tipo_doc='".$_POST["tipo_doc"]."', n_doc='".$_POST["n_doc"]."', cliente='".$_POST["cliente"]."', transportista='".$_POST["transportista"]."', guia='".$_POST["guia"]."', fecha_entrega='".$_POST["fecha_entrega"]."', valor='".$_POST["valor"]."', obs_envio='".$_POST["obs_envio"]."', fecha_at='".date("Y-m-d H:i:s")."', usuario_at='".$_SESSION['id']."', estado='2', adjunto_envio='".$nombre_archivo."' WHERE id='".$_POST["op"]."' ";

		
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

	header('Location: ../formato_op.php?op='.$_POST["op"].'');
?>
