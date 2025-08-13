<?php
	/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);*/
	require_once("../php/aut.php");
	include("../conexion/bdd.php");

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require '../lib/PHPMailer/src/Exception.php';
	require '../lib/PHPMailer/src/PHPMailer.php';
	require '../lib/PHPMailer/src/SMTP.php';

	foreach ($_POST["lib_p"] as $lib_p) {
		
		list($cant,$lib,$desc) =explode("/", $lib_p);

		$sql_e = "UPDATE libros_pedidos2 SET cantidad_aprob='".$cant."', descuento_aprob='".$desc."' WHERE id='".$lib."'";

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

	$sql = "UPDATE pedidos2 SET estado='2' WHERE id='".$_POST["pedido"]."'";
	$req = $bdd->prepare($sql);
	$req->execute();

	if ($_SESSION['tipo']==1) {
		
		$mail = new PHPMailer(true);

		/*try {

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
			$mail->addAddress("comercial@eurekalibros.com.co", 'comercial@eurekalibros.com.co');     // Add a recipient
				  
			//$mail->addCC("oltoledo@hotmail.com");
			//$mail->addBCC('comercial@eurekalibros.com.co');

				  
			// Content
			$mail->isHTML(true);

			$sql = "SELECT id FROM pedidos2 WHERE id='".$_POST["pedido"]."'";

			$req = $bdd->prepare($sql);
			$req->execute();
			$pedido = $req->fetch();
			                                  // Set email format to HTML
			$mail->Subject = 'Solicitud de pedido distribuidor #'.$pedido["id"].'';

			$sq_l2 = "SELECT CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE id='".$_SESSION["id"]."'";
														
			$req_l2 = $bdd->prepare($sq_l2);
			$req_l2->execute();
			$promo = $req_l2->fetch();

			$mail->Body    = '<p style="font-size: 17px;">El distribuidor: '.$promo["promotor"].' hizo la solicitud de pedido #'.$pedido["id"].'. Haz clic <a href="https://eurekalibros.com.co/promotores/pedido_colegio2.php?id_pedido_dist='.$pedido['id'].' ">aquí</a> para revisarlo<p>';

			$mail->AltBody = 'probandosss';

			$mail->CharSet = 'UTF-8';

			$mail->send();
				//echo "<script>alert('We have sent a message to your registered email. Check your Inbox or check your Spam Mail folder.');window.location='../index.php';</script>";
		} catch (Exception $e) {

			echo "An error has occurred please try again: {$mail->ErrorInfo}";
		}*/

	}

	header('Location: ../lista_pedidos_sa.php?tp=2');

?>