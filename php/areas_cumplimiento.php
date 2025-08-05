<?php

ini_set('memory_limit', '200000M');

	require_once('../conexion/bdd.php');
	
		$sql_periodo="SELECT id FROM periodos WHERE id='".$_POST["periodo"]."'";


		$req_periodo = $bdd->prepare($sql_periodo);
		$req_periodo->execute();
		$gp_periodo = $req_periodo->fetch();

		$libs_2=[];

		

		foreach ($_POST["libs_aod"] as $libros => $libro) {

			list($materia,$grado,$lib,$grado_otro) = explode("/", $libro);


			if ($grado != 17) {

				if ($lib !=0) {

					$sql_p="INSERT INTO areas_objetivas(id_periodo,id_colegio,id_materia,id_grado,id_grado_otro,id_libro_eureka,definicion) VALUES('".$gp_periodo["id"]."','".$_POST["id_colegio"]."', '".$materia."', '".$grado."', '".$grado_otro."' ,'".$lib."','1')";
				}
			}else{
				if ($lib !=0) {

					do {
			        	$caracteres = "123456789"; //posibles caracteres a usar
			         	$numerodeletras=11; //numero de letras para generar el texto
			         	$cod_area =""; //variable para almacenar la cadena generada
			         	for($i=0;$i<$numerodeletras;$i++)
			         	{
			            	$cod_area .=substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
			            	entre el rango 0 a Numero de letras que tiene la cadena */
			         	}
				        $sql = "SELECT codigo FROM areas_objetivas";

						$req = $bdd->prepare($sql);
						$req->execute();
						$codigos = $req->fetchAll();

			         	foreach($codigos as $codigo) {
							if ($cod_area !="") {
								if (($codigo["codigo"]==$cod_area)) $cod_area="";
							}
						}
	   
	      			} while ($cod_area=="");

					$sql_p="INSERT INTO areas_objetivas(codigo,id_periodo,id_colegio,id_materia,id_grado,id_grado_otro,id_libro_eureka,definicion) VALUES('".$cod_area."','".$gp_periodo["id"]."','".$_POST["id_colegio"]."', '".$materia."', '".$grado."', '".$grado_otro."' ,'".$lib."','1')";
				}

			}

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
		

			if ($grado== 15 || $grado ==16) {
				$sq_l2 = "SELECT id FROM libros WHERE pri_sec='".$lib."'";
															
				$req_l2 = $bdd->prepare($sq_l2);
				$req_l2->execute();
				$libros2 = $req_l2->fetchAll();

				foreach ($libros2 as $libro2) {

					$libs_2[]=$libro2["id"];
					

				}

			}

			elseif ($grado == 17) {

				if ($lib !=0) {

					$sql_e = "INSERT INTO presupuestos(id_periodo,id_colegio, id_libro,cod_area, precio, tasa_compra_d,descuento_d,aprobado,pre_definido,id_usuario, cod_zona, sub_zona, responsable) values ('".$gp_periodo["id"]."','".$_POST["id_colegio"]."','".$lib."','".$cod_area."','0', '0', '0.20','0','1','".$_POST["promotor"]."', '".$_POST["cod_zona"]."', '".$_POST["sub_zona"]."', '".$_POST["responsable"]."')";
					
					$query_e = $bdd->prepare( $sql_e );
					if ($query_e == false) {
						print_r($bdd->errorInfo());
							die ('Erreur prepare');
					}
					$sth_e = $query_e->execute();
					if ($sth_e == false) {
						print_r($query_e->errorInfo());
						die ('Erreur execute');
					}
				}
				
			}

			else {
				if ($lib !=0) {

					$sql_e = "INSERT INTO presupuestos(id_periodo,id_colegio, id_libro, precio, tasa_compra_d,descuento_d,aprobado,pre_definido,id_usuario, cod_zona, sub_zona, responsable) values ('".$gp_periodo["id"]."','".$_POST["id_colegio"]."','".$lib."','0', '0', '0.20','0','1','".$_POST["promotor"]."', '".$_POST["cod_zona"]."', '".$_POST["sub_zona"]."', '".$_POST["responsable"]."')";

					$query_e = $bdd->prepare( $sql_e );
					if ($query_e == false) {
						print_r($bdd->errorInfo());
							die ('Erreur prepare');
					}
					$sth_e = $query_e->execute();
					if ($sth_e == false) {
						print_r($query_e->errorInfo());
						die ('Erreur execute');
					}
				}
			}

		}

		if(!empty($libs_2)) {

			$libs_2=array_unique($libs_2);

			foreach ($libs_2 as $lib2) {

				$sql_e = "INSERT INTO presupuestos(id_periodo,id_colegio, id_libro, precio, tasa_compra_d,descuento_d,aprobado,pre_definido,id_usuario, cod_zona, sub_zona, responsable) values ('".$gp_periodo["id"]."','".$_POST["id_colegio"]."','".$lib2."','0', '0', '0.20','0','1','".$_POST["promotor"]."', '".$_POST["cod_zona"]."', '".$_POST["sub_zona"]."', '".$_POST["responsable"]."')";

				$query_e = $bdd->prepare( $sql_e );
				if ($query_e == false) {
					print_r($bdd->errorInfo());
						die ('Erreur prepare');
				}
				$sth_e = $query_e->execute();
				if ($sth_e == false) {
					print_r($query_e->errorInfo());
					die ('Erreur execute');
				}

			}

		}
		


		

	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=adopciones');
	
?>