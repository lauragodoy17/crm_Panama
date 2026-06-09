<?php

    include("../conexion/bdd.php");
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("registrar_historial.php");

    $id_usuario_h = intval($_SESSION["id"] ?? 0);

    foreach ($_POST["adm"] as $adms => $adm) {

        if ($adm == "") continue;

        $parts = explode("/", $adm);
        if (count($parts) < 5) continue;

        list($nombre,$apellido,$correo, $telefono, $cargo) = $parts;

        if ($adm !="") {

            $sql_p = "INSERT INTO trabajadores_colegios(id_colegio, nombre, apellido, email, telefono, cargo) VALUES('{$_POST['id_colegio']}', '{$nombre}','{$apellido}','{$correo}','{$telefono}','{$cargo}')";


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

            registrar_historial($bdd, $_POST['id_colegio'], $id_usuario_h, 'Información de contacto',
                'Nuevo contacto administrativo', '', "$nombre $apellido");
        }


    }

    header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=info_contac');

?>
