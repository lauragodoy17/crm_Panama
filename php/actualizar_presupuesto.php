<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	if (isset($_POST["presupuesto_p"])) {



		$sql_fcole = "SELECT MAX(fila_zona) as fila_zona FROM presupuestos WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."'";

		$req_fcole = $bdd->prepare($sql_fcole);
		$req_fcole->execute();
		$fcole = $req_fcole->fetch();

		if ($fcole["fila_zona"] > 0) {

			$fila_zona= $fcole["fila_zona"];

		}else {


			$sql_zona = "SELECT cod_zona FROM colegios WHERE id='".$_POST["id_colegio"]."'";

			$req_zona = $bdd->prepare($sql_zona);
			$req_zona->execute();
			$zona = $req_zona->fetch();


			$sql = "SELECT MAX(fila_zona) as fila_zona FROM presupuestos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona WHERE p.id_periodo='".$_POST["periodo"]."' AND z.codigo='".$zona["cod_zona"]."'";

			$req = $bdd->prepare($sql);
			$req->execute();
			$con_fila_zona = $req->fetch();

			if ($con_fila_zona["fila_zona"] > 0) {

				$fila_zona=$con_fila_zona["fila_zona"] + 1;
			}
			else {

				$fila_zona=2;
			}

		}

		$sql_fcole = "SELECT MAX(fila) as fila FROM presupuestos WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."'";

		$req_fcole = $bdd->prepare($sql_fcole);
		$req_fcole->execute();
		$fcole = $req_fcole->fetch();

		if ($fcole["fila"] > 0) {

			$fila= $fcole["fila"];

		}else {

			$sql = "SELECT MAX(fila) as fila FROM presupuestos WHERE id_periodo='".$_POST["periodo"]."'";

			$req = $bdd->prepare($sql);
			$req->execute();
			$con_fila = $req->fetch();

			if ($con_fila["fila"] > 0) {

				$fila=$con_fila["fila"] + 1;
			}
			else {

				$fila=2;
			}

		}
		
		foreach ($_POST["presupuesto_p"] as $presups => $presup) {



			list($libro,$tasa_c,$descuento,$precio, $probab) = explode("/", $presup);

				

			if ($libro !="" && $tasa_c !="") {

				$sql_cod = "SELECT p.id_libro, g.id_grado_otro FROM presupuestos p JOIN areas_objetivas g ON g.id_libro_eureka=p.id_libro WHERE g.codigo='".$libro."'";
				$req_cod = $bdd->prepare($sql_cod);
				$req_cod->execute();
				$row_cod = $req_cod->fetch();

				if ($row_cod["id_grado_otro"] == 0) {

					$sql = "SELECT columna FROM libros WHERE id='".$libro."'";

					$req = $bdd->prepare($sql);
					$req->execute();
					$con_colum = $req->fetch();	
					
					$sql_e = "UPDATE presupuestos SET precio='".$precio."', tasa_compra='".$tasa_c."', descuento='".$descuento."', probabilidad='".$probab."', fecha='".date("Y-m-d")."', fila='".$fila."', fila_zona='".$fila_zona."', columna='".$con_colum["columna"]."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND id_libro='".$libro."'";


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
					
				}else{

					$sql = "SELECT l.columna FROM libros l JOIN areas_objetivas a ON l.id=a.id_libro_eureka WHERE a.codigo='".$libro."'";

					$req = $bdd->prepare($sql);
					$req->execute();
					$con_colum = $req->fetch();

					echo $con_colum["columna"]."<br>";
					
					$sql_e = "UPDATE presupuestos SET precio='".$precio."', tasa_compra='".$tasa_c."', descuento='".$descuento."', probabilidad='".$probab."', fecha='".date("Y-m-d")."', fila='".$fila."', fila_zona='".$fila_zona."', columna='".$con_colum["columna"]."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND cod_area='".$libro."'";

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

	}else{
		
		foreach ($_POST["presupuesto_p"] as $presups => $presup) {
			
			list($libro,$tasa_c,$descuento,$precio, $probab) = explode("/", $presup);


			if ($libro !="" && $tasa_c !="") {
				$sql_cod = "SELECT p.id_libro, g.id_grado_otro FROM presupuestos p JOIN areas_objetivas g ON g.id_libro_eureka=p.id_libro WHERE g.codigo='".$libro."'";
				$req_cod = $bdd->prepare($sql_cod);
				$req_cod->execute();
				$row_cod = $req_cod->fetch();

				if ($row_cod["id_grado_otro"] == 0) {
					

					$sql_e = "UPDATE presupuestos SET precio='".$precio."', tasa_compra='".$tasa_c."', descuento='".$descuento."', probabilidad='".$probab."', fecha='".date("Y-m-d")."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND (id_libro='".$libro."' OR cod_area='".$libro."')";


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
					
				}else{

				

					$sql_e = "UPDATE presupuestos SET precio='".$precio."', tasa_compra='".$tasa_c."', descuento='".$descuento."', probabilidad='".$probab."', fecha='".date("Y-m-d")."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND cod_area='".$libro."'";

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

	}

	
	header('Location: ../colegio.php?codigo='.$_POST["codigo"].'&periodo='.$_POST["periodo"].'&tab=presupuesto');
	
	
	


?>