<?php
	require_once("aut.php");
	include("../conexion/bdd.php");

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require '../lib/PHPMailer/src/Exception.php';
	require '../lib/PHPMailer/src/PHPMailer.php';
	require '../lib/PHPMailer/src/SMTP.php';


	$sql = "SELECT c.id as colegio, u.id FROM colegios c JOIN usuarios u ON c.cod_zona=u.cod_zona WHERE c.codigo='".$_GET["cod_colegio"]."' ";
    $req = $bdd->prepare($sql);
    $req->execute();
	$cod_user = $req->fetch();


	if (isset($_GET["aprobar"])) {
		
		$sql = "UPDATE solicitudes_recursos SET estado='2' WHERE id='".$_GET["solicitud"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		
		$sql_p = "INSERT INTO notificaciones (id_periodo,id_colegio,id_usuario,id_solicitud,id_tipo_notifi,visible) VALUES('".$_GET["periodo"]."','".$cod_user["colegio"]."','".$cod_user["id"]."','".$_GET["solicitud"]."','8','1')";
				
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


		$sql = "SELECT usuario,id_colegio FROM solicitudes_recursos WHERE id='".$_GET["solicitud"]."'";
	    $req = $bdd->prepare($sql);
	    $req->execute();
		$solicitud = $req->fetch();

		$sq_l2 = "SELECT CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE id='".$solicitud["usuario"]."'";
														
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
			$mail->addAddress("info@eurekalibros.com.co", 'felipe.vargas@eurekalibros.com.co');     // Add a recipient
				  
			//$mail->addCC("oltoledo@hotmail.com");
			//$mail->addBCC('comercial@eurekalibros.com.co');

				  
			// Content
			$mail->isHTML(true);

			
			                                  // Set email format to HTML
			$mail->Subject = 'Solicitud de recursos #'.$_GET["solicitud"].' Aprobada';

			

			$mail->Body    = '<p style="font-size: 17px;">'.$promo["promotor"].' le fue aprobada la solicitud #'.$_GET["solicitud"].' para: '.$cole["colegio"].'';

			$mail->AltBody = 'probandosss';

			$mail->CharSet = 'UTF-8';

			$mail->send();
				//echo "<script>alert('We have sent a message to your registered email. Check your Inbox or check your Spam Mail folder.');window.location='../index.php';</script>";
		} catch (Exception $e) {

			echo "An error has occurred please try again: {$mail->ErrorInfo}";
		}


	}elseif (isset($_GET["rechazar"])) {

		$sql = "UPDATE solicitudes_recursos SET estado='3' WHERE id='".$_GET["solicitud"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		
		$sql_p = "INSERT INTO notificaciones (id_periodo,id_colegio,id_usuario,id_solicitud,id_tipo_notifi,visible) VALUES('".$_GET["periodo"]."','".$cod_user["colegio"]."','".$cod_user["id"]."','".$_GET["solicitud"]."','9','1')";
				
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


	}elseif (isset($_GET["contab"])) {

		$sql = "UPDATE solicitudes_recursos SET contab=1 WHERE id='".$_GET["solicitud"]."'";
		$req = $bdd->prepare($sql);
		$req->execute();
		


	}

	header("Location: ../vista_solicitud.php?id=".$_GET["solicitud"]."&updated=1");

	
?>
