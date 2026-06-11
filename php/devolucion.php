<?php 
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

		
	do {
	         $caracteres = "1234567890"; //posibles caracteres a usar
	         $numerodeletras=10; //numero de letras para generar el texto
	         $cod_pedido =""; //variable para almacenar la cadena generada
	         for($i=0;$i<$numerodeletras;$i++)
	         {
	            $cod_pedido .=substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
	            entre el rango 0 a Numero de letras que tiene la cadena */
	         }
	        $sql = "SELECT codigo FROM pedidos";

			$req = $bdd->prepare($sql);
			$req->execute();
			$codigos = $req->fetchAll();

	         foreach($codigos as $codigo) {
				if ($cod_pedido !="") {
					if (($codigo["codigo"]==$cod_pedido)) $cod_pedido="";
				}
			}
	   
	 } while ($cod_pedido=="");


	foreach ($_POST["libro"] as $libros => $libro) {

		list($id_libro,$cantidad,$cod_area) = explode("/", $libro);
			
		if ($libro !=0) {

			if ($cantidad > 0) {
				
				$sql_p = "INSERT INTO libros_devol_v(cod_pedido,id_libro,cod_area,cantidad) VALUES('".$cod_pedido."','".$id_libro."','".$cod_area."','".$cantidad."')";
				
				
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

	$sql_p2 = "INSERT INTO devoluciones_v(codigo,id_periodo,id_colegio,id_usuario,observaciones,cliente,tipo,estado) VALUES('".$cod_pedido."','".$_POST["periodo"]."','".$_POST["id_colegio"]."','".$_SESSION["id"]."','".$_POST["observaciones"]."','".$_POST["cliente"]."','".$_POST["tipo"]."','1')";
				
				
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




	header("Location: ../colegios_devols.php?periodo=".$_POST["periodo"]."&ink_status=ok&ink_msg=".urlencode('Devolución de venta registrada correctamente'));
	exit;
	
?>