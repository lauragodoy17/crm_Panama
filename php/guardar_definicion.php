<?php
	require_once("../php/aut.php");
	include("../conexion/bdd.php");



	foreach ($_POST["presupuesto_d"] as $presups => $presup) {

		list($libro,$tasa_c,$descuento, $precio,$precio_padre) = explode("/", $presup);

		

		if ($tasa_c=="") {

			$sql_cod = "SELECT p.id_libro, g.id_grado_otro,g.codigo FROM presupuestos p JOIN areas_objetivas g ON g.id_libro_eureka=p.id_libro WHERE g.codigo='".$libro."'";
			$req_cod = $bdd->prepare($sql_cod);
			$req_cod->execute();

			$row_cod = $req_cod->fetch();

			if ($row_cod["id_grado_otro"] == 0) {

				$sql2 = "SELECT id,tasa_compra,descuento,tasa_compra_d,descuento_d FROM presupuestos WHERE id='".$libro."' AND id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

			}else{

				$sql2 = "SELECT id,tasa_compra,descuento,tasa_compra_d,descuento_d FROM presupuestos WHERE cod_area='".$row_cod["codigo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

			}
			$req2 = $bdd->prepare($sql2);
			$req2->execute();
			$row2 = $req2->fetch();	

			if ($row2["tasa_compra_d"]==0.00) {
		
				if ($row_cod["id_grado_otro"] == 0) {

					$sql_e = "UPDATE presupuestos SET tasa_compra_d='".$row2["tasa_compra"]."', descuento_d='".$row2["descuento"]."' WHERE id='".$row2["id"]."'";

				}else{

					$sql_e = "UPDATE presupuestos SET tasa_compra_d='".$row2["tasa_compra"]."', descuento_d='".$row2["descuento"]."' WHERE cod_area='".$row_cod["codigo"]."'";
				}

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

		

		}else {

			$sql_cod = "SELECT p.id_libro, g.id_grado_otro,g.codigo FROM presupuestos p JOIN areas_objetivas g ON g.id_libro_eureka=p.id_libro WHERE g.codigo='".$libro."'";
			$req_cod = $bdd->prepare($sql_cod);
			$req_cod->execute();

			$row_cod = $req_cod->fetch();

			if ($row_cod["id_grado_otro"] == 0) {
				$sql_e = "UPDATE presupuestos SET  tasa_compra_d='".$tasa_c."',descuento_d='".$descuento."', precio='".$precio."', precio_venta_final='".$precio_padre."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND id_libro='".$libro."'";

			}else{
				
				$sql_e = "UPDATE presupuestos SET  tasa_compra_d='".$tasa_c."',descuento_d='".$descuento."', precio='".$precio."', precio_venta_final='".$precio_padre."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND cod_area='".$row_cod["codigo"]."'";
			}

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

	$sql_rec = "SELECT id FROM recursos WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."'";

	$req_rec = $bdd->prepare($sql_rec);
	$req_rec->execute();
	$num = $req_rec->rowCount();

	if ($num < 1 ) {

		$sql_e = "INSERT INTO recursos(id_periodo,id_colegio,recurso,valor_recurso, reintegro, valor_reintegro,id_canal,descripcion_canal,venta_real,fecha,observaciones) VALUES ('".$_POST["periodo"]."', '".$_POST["id_colegio"]."', '".$_POST["recurso"]."','".$_POST["valor_recurso"]."', '".$_POST["reintegro"]."','".$_POST["valor_reintegro"]."','".$_POST["canal"]."','".$_POST["descripcion"]."','".$_POST["venta_real"]."','".date("Y-m-d")."','".$_POST["observaciones"]."')";

	}else {

		$sql_e = "UPDATE recursos SET recurso='".$_POST["recurso"]."', valor_recurso='".$_POST["valor_recurso"]."', reintegro='".$_POST["reintegro"]."', valor_reintegro='".$_POST["valor_reintegro"]."', id_canal='".$_POST["canal"]."', descripcion_canal='".$_POST["descripcion"]."',venta_real='".$_POST["venta_real"]."' , fecha='".date("Y-m-d")."', observaciones='".$_POST["observaciones"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
	}

	

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


	foreach($_POST["presup_profes"] as $presup_p) {

		$sql_e = "DELETE FROM presup_profes WHERE id_presup='".$presup_p."'";

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

	

	foreach($_POST["presup_profes"] as $presup_p) {



		foreach($_POST["profes".$presup_p.""] as $profe) {



			$sql_e = "INSERT INTO presup_profes(id_presup,id_profe) VALUES ('".$presup_p."','".$profe."')";

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

		

	
		
	$sql_nconse = "SELECT conse FROM presupuestos WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."'";

	$req_nconse = $bdd->prepare($sql_nconse);
	$req_nconse->execute();
	$nconse = $req_nconse->fetch();

	if ($nconse["conse"] ==0 ) {

		$sql_fcole = "SELECT MAX(conse+1) as conse FROM presupuestos WHERE id_periodo='".$_POST["periodo"]."'";

		$req_fcole = $bdd->prepare($sql_fcole);
		$req_fcole->execute();
		$conse = $req_fcole->fetch();
	}

	
	//Busco los que ya estan definidos en ese colegio
	$sqld = "SELECT id FROM presupuestos WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."' AND definido='1'";
	$reqd = $bdd->prepare($sqld);
	$reqd->execute();
	$s_defs = $reqd->fetchAll();

	foreach($s_defs as $s_def) {
		$defs[]=$s_def["id"];

	}
		
	if (isset($_POST["definir"]) ) {
		
		foreach ($_POST["definir"] as $definiciones => $definir) {

			list($libro,$id_presupuesto) = explode("/", $definir);
			
			//almaceno en un array los que se marcaron como definidos
			$defs2[]=$id_presupuesto;

			$sql2 = "SELECT aprobado FROM presupuestos WHERE id='".$id_presupuesto."'";
			$req2 = $bdd->prepare($sql2);
			$req2->execute();
			$row2 = $req2->fetch();	

			if ($row2["aprobado"]==1) {

				if ($nconse["conse"] ==0 ) {

					$sql_e = "UPDATE presupuestos SET definido='1', conse='".$conse["conse"]."' WHERE id='".$id_presupuesto."'";

				}else{
					$sql_e = "UPDATE presupuestos SET definido='1', conse='".$nconse["conse"]."' WHERE id='".$id_presupuesto."'";
				}

				
				

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

				if ($nconse["conse"] ==0 ) {

					$sql_e = "UPDATE presupuestos SET definido='1', conse='".$conse["conse"]."' WHERE id='".$id_presupuesto."'";

				}else{
						
					$sql_e = "UPDATE presupuestos SET definido='1', conse='".$nconse["conse"]."' WHERE id='".$id_presupuesto."'";
				}

					

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

		foreach ($defs as $d => $valor) {
			//Buscos los que estan definidos en el colegio en el arreglo de los que se marcaron como definidos
			if (!in_array($valor, $defs2)) {


				$sql_e = "UPDATE presupuestos SET definido='0' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."' AND id='".$valor."'";

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

		$sql_e = "UPDATE colegios_status SET id_status='1' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

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
		$sql_e = "UPDATE presupuestos SET definido='0' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

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
		

	$sql_z = "INSERT INTO notificaciones (id_periodo,id_colegio,id_usuario,id_tipo_notifi,usuario_respuesta,visible) VALUES ('".$_POST["periodo"]."','".$_POST["id_colegio"]."','0', '1', '0', '1')";
		
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


	foreach ($_POST["v_uni_vr"] as $v_uni_vrs => $v_uni_vr) {

		list($libro,$uni_vr) = explode("/", $v_uni_vr);

		
		if ($libro!="") {

			$sql_cod = "SELECT p.id_libro, g.id_grado_otro,g.codigo FROM presupuestos p JOIN areas_objetivas g ON g.id_libro_eureka=p.id_libro WHERE g.codigo='".$libro."'";
			$req_cod = $bdd->prepare($sql_cod);
			$req_cod->execute();

			$row_cod = $req_cod->fetch();

			if ($row_cod["id_grado_otro"] == 0) {
						
				$sql_e = "UPDATE presupuestos SET  uni_vr='".$uni_vr."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND id_libro='".$libro."'";

			}else{
						
				$sql_e = "UPDATE presupuestos SET  uni_vr='".$uni_vr."' WHERE id_periodo='".$_POST["periodo"]."' AND id_colegio='".$_POST["id_colegio"]."' AND cod_area='".$row_cod["codigo"]."'";
			}

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
	
	header('Location: ../colegio.php?codigo='.$_POST["codigo"].'&periodo='.$_POST["periodo"].'&tab=adopciones');

?>