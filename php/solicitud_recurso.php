<?php

	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/

	require_once("aut.php"); 
	require_once('../conexion/bdd.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require '../lib/PHPMailer/src/Exception.php';
	require '../lib/PHPMailer/src/PHPMailer.php';
	require '../lib/PHPMailer/src/SMTP.php';


	$sql = "SELECT MAX(conse) as conse FROM solicitudes_recursos";
    $req = $bdd->prepare($sql);
    $req->execute();
	$conse = $req->fetch();

	$conse["conse"]++;
	
	$sql_p = "INSERT INTO solicitudes_recursos(id_periodo,usuario,id_colegio,estado,solicitante,fecha_entrega,reintegro, conse) VALUES('".$_POST["periodo"]."', '".$_SESSION["id"]."', '".$_POST["id_colegio"]."','1', '".$_POST["solicitante"]."','".$_POST["fecha_entrega"]."','".$_POST["reintegro"]."', '".$conse["conse"]."')";
				
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


	$sql = "SELECT id, id_colegio FROM solicitudes_recursos ORDER BY id DESC";
    $req = $bdd->prepare($sql);
    $req->execute();
	$solicitud = $req->fetch();

	foreach ($_POST["areas_r"] as $libros => $libro) {

		list($materia,$preescolar,$primaria,$bachillerato) = explode("/", $libro);

		if ($materia !=0) {
			
			$sql_p = "INSERT INTO areas_recursos(id_solicitud,materia,preescolar,primaria,bachillerato) VALUES('".$solicitud["id"]."', '".$materia."', '".$preescolar."', '".$primaria."','".$bachillerato."')";
				
				
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

	foreach ($_POST["recursos"] as $recurso_ats => $recurso_at) {



		list($recurso,$tipo,$categoria,$presupuesto) = explode("/", $recurso_at);

		if ($recurso !="") {
	
			$sql_p = "INSERT INTO recursos_solicitados(id_solicitud,tipo,categoria,recurso,presupuesto) VALUES('".$solicitud["id"]."', '".$tipo."', '".$categoria."','".$recurso."', '".$presupuesto."')";
				
				
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

	$sql_z = "INSERT INTO notificaciones (id_periodo,id_colegio,id_usuario,id_solicitud,id_tipo_notifi,usuario_respuesta,visible) VALUES ('".$_POST["periodo"]."','".$_POST["colegio"]."','0','".$solicitud["id"]."', '7', '0', '1')";
	
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

	$sq_l2 = "SELECT CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE id='".$_SESSION["id"]."'";
														
	$req_l2 = $bdd->prepare($sq_l2);
	$req_l2->execute();
	$promo = $req_l2->fetch();

	$sq_l3 = "SELECT colegio FROM colegios WHERE id='".$solicitud["id_colegio"]."'";
														
	$req_l3 = $bdd->prepare($sq_l3);
	$req_l3->execute();
	$cole = $req_l3->fetch();

	$mail = new PHPMailer(true);

	try {

		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                      // OFF verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = 'mail.eurekalibros.com.co';                    // Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->SMTPAutoTLS = false; 
	    $mail->Username   = 'crm@eurekalibros.com.co';                     // SMTP username
	    $mail->Password   = 'cRm14356$';                              // SMTP password
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_S	above                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		//Recipients
		$mail->setFrom('crm@eurekalibros.com.co', 'CRM Eureka');
		$mail->addAddress("felipe.vargas@eurekalibros.com.co", 'felipe.vargas@eurekalibros.com.co');     // Add a recipient
			  
		//$mail->addCC("oltoledo@hotmail.com");
		//$mail->addBCC('comercial@eurekalibros.com.co');

			  
		// Content
		$mail->isHTML(true);

		
		                                  // Set email format to HTML
		$mail->Subject = 'Solicitud de recursos #'.$solicitud["id"].'';

		

		$mail->Body    = '<p style="font-size: 17px;">'.$promo["promotor"].' hizo la solicitud de recursos #'.$solicitud["id"].' para: '.$cole["colegio"].'';

		$mail->AltBody = 'probandosss';

		$mail->CharSet = 'UTF-8';

		$mail->send();
			//echo "<script>alert('We have sent a message to your registered email. Check your Inbox or check your Spam Mail folder.');window.location='../index.php';</script>";
	} catch (Exception $e) {

		echo "An error has occurred please try again: {$mail->ErrorInfo}";
	}


	header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=atenciones');
	
?>