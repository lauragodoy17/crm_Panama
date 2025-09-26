<?php 
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

	header("Content-Type:text/html;charset=utf-8");	
	if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2 || $_SESSION["id"] ==19) {

		$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/promotores/adjuntos/';
		$nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
		$fichero_subido = $dir_subida . basename($nombre_archivo);
		$estado=2;

	}else{
		$nombre_archivo="";
		$estado=1;
	}

	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
		echo "archivo subido";
	}

	do {
	         $caracteres = "1234567890"; //posibles caracteres a usar
	         $numerodeletras=10; //numero de letras para generar el texto
	         $cod_pedido =""; //variable para almacenar la cadena generada
	         for($i=0;$i<$numerodeletras;$i++)
	         {
	            $cod_pedido .=substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
	            entre el rango 0 a Numero de letras que tiene la cadena */
	         }

	         if ($_POST["tp"]==1) {
	         	$sql = "SELECT codigo FROM devoluciones";
	         }elseif ($_POST["tp"]==2){
	         	$sql = "SELECT codigo FROM devoluciones_prov";
	         }else{
	         	$sql = "SELECT codigo FROM devoluciones_v";
	         }
	        

			$req = $bdd->prepare($sql);
			$req->execute();
			$codigos = $req->fetchAll();

	         foreach($codigos as $codigo) {
				if ($cod_pedido !="") {
					if (($codigo["codigo"]==$cod_pedido)) $cod_pedido="";
				}
			}
	   
	 } while ($cod_pedido=="");


	foreach ($_POST["libro_e"] as $libros => $libro) {

		list($id_libro,$cantidad) = explode("/", $libro);
			
		if ($libro !=0) {

			if ($cantidad > 0) {
			
				if ($_POST['tp']==3) {
					$sql_p = "INSERT INTO libros_devol_v(cod_pedido,id_libro,cantidad) VALUES('".$cod_pedido."','".$id_libro."','".$cantidad."')";
					
				}

				else{
					$sql_p = "INSERT INTO libros_devol(cod_pedido,id_libro,cantidad) VALUES('".$cod_pedido."','".$id_libro."','".$cantidad."')";
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

			}
		
		}

	}

	foreach ($_POST['pri_sec'] as $index => $id_libro) {
    	$cantidad = $_POST['cantidad_pri_sec'][$index];

    	if ($cantidad > 0) {
				
			$sql_g = "SELECT id_grado FROM libros WHERE id='".$id_libro."'";
			$req_g = $bdd->prepare($sql_g);
			$req_g->execute();

			$grado = $req_g->fetch();

				if ($_POST['tp']==3) {
					$sql_p = "INSERT INTO libros_devol_v(cod_pedido,id_libro,cantidad) VALUES('".$cod_pedido."','".$id_libro."','".$cantidad."')";
				}else{
					$sql_p = "INSERT INTO libros_devol(cod_pedido,id_libro,cantidad) VALUES('".$cod_pedido."','".$id_libro."','".$cantidad."')";
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

		}
	}

	$_POST["observaciones"] = str_replace(["'", '"'], "", $_POST["observaciones"]);
	if ($_POST["tp"]==1) {
		$sql_p2 = "INSERT INTO devoluciones(codigo,tipo,id_periodo,persona,id_usuario,observaciones,archivo,estado) VALUES('".$cod_pedido."','1','1','".$_POST["cliente"]."','".$_SESSION["id"]."','".$_POST["observaciones"]."','".$nombre_archivo."','".$estado."')";
	}

	elseif ($_POST["tp"]==2) {
		$sql_p2 = "INSERT INTO devoluciones_prov(codigo,tipo,id_periodo,persona,id_usuario,observaciones,archivo,estado) VALUES('".$cod_pedido."','2','1','".$_POST["persona"]."','".$_SESSION["id"]."','".$_POST["observaciones"]."','".$nombre_archivo."','".$estado."')";
	}

	else{
		$sql_p2 = "INSERT INTO devoluciones_v(codigo,id_usuario,observaciones,cliente,tipo,estado) VALUES('".$cod_pedido."','".$_SESSION["id"]."','".$_POST["observaciones"]."','".$_POST["cliente"]."','1','1')";

	}

	
				
				
		$query_p2 = $bdd->prepare( $sql_p2 );
		if ($query_p2 == false) {
			print_r($bdd->errorInfo());
			die ('Erreur prepare');
		}
		$sth_p2 = $query_p2->execute();
		if ($sth_p2 == false) {
			print_r($query_p2->errorInfo());
			die ('Erreur execute');
		}


	if ($_POST["tp"]==1) {
		$sql = "SELECT id,tipo FROM devoluciones WHERE codigo='".$cod_pedido."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		$pedido = $req->fetch();
		echo "<script>alert('Devolución registrada');window.location='../vista_devol.php?id_devol=".$pedido["id"]."&tipo=".$pedido["tipo"]."';</script>";
	}

	elseif ($_POST["tp"]==2) {
		$sql = "SELECT id,tipo FROM devoluciones_prov WHERE codigo='".$cod_pedido."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		$pedido = $req->fetch();
		echo "<script>alert('Devolución registrada');window.location='../vista_devol.php?id_devol=".$pedido["id"]."&tipo=".$pedido["tipo"]."';</script>";
	}
	else{	
		$sql = "SELECT id,tipo FROM devoluciones_v WHERE codigo='".$cod_pedido."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		$pedido = $req->fetch();
		echo "<script>alert('Devolución registrada');window.location='../devolucion_colegio.php?id_pedido=".$pedido["id"]."';</script>";
	}


	
	
?>