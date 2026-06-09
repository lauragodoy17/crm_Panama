<?php
	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);
	include("../conexion/bdd.php");
	if (session_status() === PHP_SESSION_NONE) session_start();
	require_once("registrar_historial.php");

	$id_usuario_h = intval($_SESSION["id"] ?? 0);

	foreach ($_POST as $key => $value) {
	    if ($value !="") {

	    	if ($key !="id_colegio" && $key !="periodo" && $key !="cod_colegio") {
	    		
	    		list($grado,$paralelo)=explode("-", $key);

	    		//echo "grado: $grado paralelo: $paralelo alumnos: $value<br>";


		        $sql="SELECT id FROM grados_paralelos WHERE id_colegio='{$_POST['id_colegio']}' AND id_periodo='{$_POST['periodo']}' AND id_grado='{$grado}' AND paralelos='{$paralelo}'";

				$req= $bdd->prepare($sql);
				$req->execute();
				$cuenta = $req->rowCount();

				// Lookup grade name
					$req_grado_h = $bdd->prepare("SELECT grado FROM grados WHERE id=:id");
					$req_grado_h->execute([':id' => $grado]);
					$grado_row = $req_grado_h->fetch();
					$grado_nombre = $grado_row ? $grado_row['grado'] : "Grado $grado";

				if ($cuenta > 0) {

					// Fetch old value for historial
					$req_old_pob = $bdd->prepare("SELECT alumnos FROM grados_paralelos WHERE id_colegio=:ic AND id_periodo=:ip AND id_grado=:ig AND paralelos=:par");
					$req_old_pob->execute([':ic'=>$_POST['id_colegio'],':ip'=>$_POST['periodo'],':ig'=>$grado,':par'=>$paralelo]);
					$old_pob = $req_old_pob->fetch();

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

					if ($old_pob && (string)$old_pob['alumnos'] !== (string)$value) {
						registrar_historial($bdd, $_POST['id_colegio'], $id_usuario_h, 'Población',
							"Alumnos $grado_nombre - Paralelo $paralelo", $old_pob['alumnos'], $value);
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

					registrar_historial($bdd, $_POST['id_colegio'], $id_usuario_h, 'Población',
						"Alumnos $grado_nombre - Paralelo $paralelo", '', $value);

				}
		        

	    	}
	    	
	    }
	}

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=poblacion');

?>