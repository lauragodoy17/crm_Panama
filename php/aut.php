<?php
session_start();

  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
  
  date_default_timezone_set('America/Bogota');

    if ($_SESSION["autentificado"] != "SI") {
      //si no está logueado lo envío a la página de autentificación
      if (isset($_GET['id_pedido'])) {
        header("location:./login.php?id_pedido=".$_GET['id_pedido']."");
        
      }elseif (isset($_GET['id_pedido_dist'])) {
        header("location:./login.php?id_pedido_dist=".$_GET['id_pedido_dist']."");
        
      }elseif (isset($_GET['id_pedido_dist'])) {
        header("location:./login.php?id_muestreo=".$_GET['id_muestreo']."");
      }elseif (isset($_GET['opd'])) {
        header("location:./login.php?opd=".$_GET['opd']."");
      }
      else{
        header("location:../login.php");
      }
      
    }
    else {
      //sino, calculamos el tiempo transcurrido
     /*$fechaGuardada = $_SESSION["ultimoAcceso"];
      $ahora = date("Y-n-j H:i:s");
      $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));*/
          //comparamos el tiempo transcurrido
  
        /*if($tiempo_transcurrido >= 3600) {
          //si pasaron 30  minutos o más
          session_destroy(); // destruyo la sesión
          echo "<script>alert('Su sesión a caducado por inactividad');window.location='login.php';</script>";
          //header("Location: login.php"); //envío al usuario a la pag. de autenticación
          //sino, actualizo la fecha de la sesión
        }
        else {
          $_SESSION["ultimoAcceso"] = $ahora;
        }*/
     
          
    }

?>