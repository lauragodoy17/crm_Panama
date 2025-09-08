<?php

	require_once("../php/aut.php");
	require_once('../conexion/bdd.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require '../lib/PHPMailer/src/Exception.php';
	require '../lib/PHPMailer/src/PHPMailer.php';
	require '../lib/PHPMailer/src/SMTP.php';

	header("Content-Type:text/html;charset=utf-8");	
	
	$dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos_opd/';
	$nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
	$fichero_subido = $dir_subida . basename($nombre_archivo);
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
		echo "archivo subido";
	}else{
		$nombre_archivo="";
	}

	/*$sql = "SELECT MAX(conse) as conse FROM ordenes_produccion";
    $req = $bdd->prepare($sql);
    $req->execute();
	$conse = $req->fetch();

	$conse["conse"]++;*/

	$sql_p2 = "INSERT INTO ordenes_produccion(usuario,solicitante,cliente,descripcion,observaciones,adjunto,fecha_ent_s) VALUES('".$_SESSION["id"]."','".$_POST["solicitante"]."','".$_POST["cliente"]."','".$_POST["descrip"]."','".$_POST["observaciones"]."','".$nombre_archivo."','".$_POST["fecha_ent_s"]."')";
				
				
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


	$sql = "SELECT id FROM ordenes_produccion ORDER BY id DESC";

	$req = $bdd->prepare($sql);
	$req->execute();
	$pedido = $req->fetch();


	foreach ($_POST["libro_e"] as $libros => $libro) {

		list($libro,$cantidad,$enca) = explode("/", $libro);
			
		if ($libro !="") {
			
			$sql_p = "INSERT INTO libros_opd(opid,libro,encaratulado,cantidad) VALUES('".$pedido["id"]."','".$libro."','".$enca."','".$cantidad."')";
				
				
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

	

	

		/*$mail = new PHPMailer(true);

		try {

			//Server settings
			//$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                      // OFF verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
		    $mail->Host       = 'mail.somoseureka.com.co';                    // Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->SMTPAutoTLS = false; 
		    $mail->Username   = 'crm@somoseureka.com.co';                     // SMTP username
		    $mail->Password   = 'cRm14356$';                              // SMTP password
			//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_S	above                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->setFrom('crm@somoseureka.com.co', 'CRM Eureka');
			$mail->addAddress("comercial@somoseureka.com.co", 'comercial@somoseureka.com.co');     // Add a recipient
				  
			$mail->addReplyTo('crm@somoseureka.com.co', 'CRM Eureka');
			$mail->addCC("arte@somoseureka.com.co");
			$mail->addBCC("taller@somoseureka.com.co");

				  
			// Content
			$mail->isHTML(true);

			                                  // Set email format to HTML
			$mail->Subject = 'Solicitud de producción digital #25-'.$conse["conse"].'';

			

			$mail->Body    = '<p style="font-size: 17px;"> Se ha creado la solicitud de producción digital #25-'.$conse["conse"].' . Haz clic <a href="https://crm.somoseureka.com.co/opd_solicitada.php?opd='.$pedido['id'].' ">aquí</a> para revisarla<p>';

			$mail->AltBody = 'probandosss';

			$mail->CharSet = 'UTF-8';

			$mail->send();
				//echo "<script>alert('We have sent a message to your registered email. Check your Inbox or check your Spam Mail folder.');window.location='../index.php';</script>";
		} catch (Exception $e) {

			echo "An error has occurred please try again: {$mail->ErrorInfo}";
		}*/
			


		
	header("Location: ../opd_solicitada.php?opd=".$pedido['id']."");
	
	
?>