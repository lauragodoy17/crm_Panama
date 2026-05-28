<?php
  require_once("../php/aut.php");
  include("../conexion/bdd.php");

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require '../lib/PHPMailer/src/Exception.php';
  require '../lib/PHPMailer/src/PHPMailer.php';
  require '../lib/PHPMailer/src/SMTP.php';

  $sql = "SELECT id FROM libros_pedidos2 WHERE cod_pedido='".$_POST['codigo']."'";

  $req = $bdd->prepare($sql);
  $req->execute();
  $libs= $req->fetchAll();

  foreach($libs as $lib) {

    $libsp[]=$lib["id"];
  }

  $resultados = array_diff($libsp, $_POST['lpid']);

  foreach($resultados as $resultado) {

    $sql = "DELETE FROM `libros_pedidos2` WHERE id='".$resultado."'";

    $req = $bdd->prepare($sql);
    $req->execute();

  }

  foreach ($_POST["libro_e"] as $libros => $libro) {

    list($id_libro,$cantidad,$descuento) = explode("/", $libro);
      
    if ($libro !=0) {
      
      $sql_p = "INSERT INTO libros_pedidos2(cod_pedido,id_libro,cantidad,descuento) VALUES('".$_POST['codigo']."','".$id_libro."','".$cantidad."','".$descuento."')";
        
        
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

  


  if ($_SESSION["tipo"] ==6 || $_SESSION["tipo"] ==7 || $_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==4 || $_SESSION["tipo"] ==2) {

    $sql_e = "UPDATE pedidos2 SET observaciones='".$_POST["observaciones"]."', verify='1' WHERE id='".$_POST["pedido"]."'";

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

    $mail = new PHPMailer(true);

    try {

      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                      // OFF verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'somoseureka.com.co';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->SMTPAutoTLS = false; 
      $mail->Username   = 'crm@somoseureka.com.co';                     // SMTP username
      $mail->Password   = 'cRm14356$';                              // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 587;  
      $mail->SMTPOptions = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      ]
    ];                                  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_S above                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail->setFrom('crm@somoseureka.com.co', 'CRM Eureka');
      $mail->addAddress("felipe.vargas@somoseureka.com.co", 'felipe.vargas@somoseureka.com.co');     // Add a recipient
          
      $mail->addReplyTo('crm@somoseureka.com.co', 'CRM Eureka');
      $mail->addCC("comercial@somoseureka.com.co");
      //$mail->addCC("oltoledo@hotmail.com");
      //$mail->addBCC('comercial@somoseureka.com.co');

          
      // Content
      $mail->isHTML(true);

      $sql = "SELECT id FROM pedidos2 WHERE codigo='".$_POST['codigo']."'";

      $req = $bdd->prepare($sql);
      $req->execute();
      $pedido = $req->fetch();
                                        // Set email format to HTML
      $mail->Subject = 'Solicitud de pedido sin adopción #'.$pedido["id"].'';

      $sq_l2 = "SELECT CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE id='".$_SESSION["id"]."'";
                            
      $req_l2 = $bdd->prepare($sq_l2);
      $req_l2->execute();
      $promo = $req_l2->fetch();

      $mail->Body    = '<p style="font-size: 17px;">El usuario: '.$promo["promotor"].' hizo la solicitud de pedido sin adopción #'.$pedido["id"].' para: '.$_POST["colegio"].'. Haz clic <a href="https://crm.somoseureka.com.co/pedido_colegio_sa.php?id_pedido_dist='.$pedido['id'].'&tp=2">aquí</a> para revisarlo<p>';

      $mail->AltBody = 'probandosss';

      $mail->CharSet = 'UTF-8';

      $mail->send();
        //echo "<script>alert('We have sent a message to your registered email. Check your Inbox or check your Spam Mail folder.');window.location='../index.php';</script>";
    } catch (Exception $e) {

      echo "An error has occurred please try again: {$mail->ErrorInfo}";
    }

    
    
    echo "<script>alert('Pedido Solicitado');window.location='../pedido_colegio_sa.php?id_pedido=".$_POST["pedido"]."&tp=2';</script>";
    
  }else{

    $sql_e = "UPDATE pedidos2 SET observaciones='".$_POST["observaciones"]."' WHERE id='".$_POST["pedido"]."'";

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

    $tp = $_POST['tp'] ?? '';
    if ($_POST['salida']=="pendiente") {

      header('Location: ../pedido_colegio_sa.php?id_pedido='.$_POST["pedido"].'&tp='.$tp);
    }elseif ($_POST['salida']=="aprobado") {
      header('Location: ../pedido_colegio_sa.php?id_pedido='.$_POST["pedido"].'&tp='.$tp);
    }else{
      header('Location: ../pedido_colegio_sa.php?id_pedido='.$_POST["pedido"].'&tp='.$tp);
    }

  }


  

  

?>