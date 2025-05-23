<?php
	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);
	include("../conexion/bdd.php");

	foreach ($_POST as $key => $value) {
	    if ($value !="") {

	    	if ($key !="id_colegio" && $key !="periodo" && $key !="cod_colegio") {
	    		
	    		list($grado,$paralelo)=explode("-", $key);

	    		//echo "grado: $grado paralelo: $paralelo alumnos: $value<br>";


		        $sql="SELECT id FROM grados_paralelos WHERE id_colegio='{$_POST['id_colegio']}' AND id_periodo='{$_POST['periodo']}' AND id_grado='{$grado}' AND paralelos='{$paralelo}'";

				$req= $bdd->prepare($sql);
				$req->execute();
				$cuenta = $req->rowCount();

				if ($cuenta > 0) {
					
					$sql_p="UPDATE grados_paralelos SET alumnos='{$value}' WHERE id_colegio='{$_POST['id_colegio']}' AND id_periodo='{$_POST['periodo']}' AND id_grado='{$grado}' AND paralelos='{$paralelo}'";
		
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

				}else{
					
					$sql_p="INSERT INTO grados_paralelos(id_periodo,id_colegio,id_grado,paralelos,alumnos) VALUES('{$_POST['periodo']}','{$_POST['id_colegio']}','{$grado}','{$paralelo}','{$value}')";

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
	    	
	    }
	}

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=poblacion');

?>