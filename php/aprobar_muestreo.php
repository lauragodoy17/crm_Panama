<?php 
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');
	

	foreach ($_POST["libro_m"] as $libros => $libro) {

		list($id_libro,$cantidad) = explode("/", $libro);
			
		if ($libro !=0) {
			
			$sql_p = "UPDATE libros_muestreos SET cantidad_aprob='".$cantidad."' WHERE id='".$id_libro."'";
				
				
			$query_p = $bdd->prepare( $sql_p );
			if ($query_p == false) {
				print_r($bdd->errorInfo());
				die ('Erreur prepare');
			}
			$sth_p = $query_p->execute();
			if ($sth_p == false) {
				print_r($query_p->errorInfo());
				die ('Erreur execute');
			}

		}
		

	}

	
	$sql = "UPDATE muestreos SET estado='2', observaciones='".$_POST["observaciones"]."' WHERE id='".$_POST["id_muestreo"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		
		
	echo "<script>alert('Muestreo aprobado');window.location='../lista_muestreo.php?tp=2';</script>";
	
?>