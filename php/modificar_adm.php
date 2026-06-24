<?php

    include("../conexion/bdd.php");
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("registrar_historial.php");

    $id_usuario_h = intval($_SESSION["id"] ?? 0);

    // Fetch old values and id_colegio before UPDATE
    $req_old_adm = $bdd->prepare("SELECT id_colegio, nombre, apellido, telefono, email, cargo FROM trabajadores_colegios WHERE id=:id");
    $req_old_adm->execute([':id' => $_POST['id_adm']]);
    $old_adm = $req_old_adm->fetch();

    $cargo_adm = ($_POST['cargo_adm'] === 'otro')
        ? trim($_POST['cargo_otro_adm'] ?? '')
        : $_POST['cargo_adm'];

    $sql = "UPDATE trabajadores_colegios SET nombre='{$_POST['nombre_adm']}', apellido='{$_POST['apellido_adm']}', telefono='{$_POST['telefono_adm']}', email='{$_POST['correo_adm']}', cargo='{$cargo_adm}' WHERE id='{$_POST['id_adm']}'  ";

    $req = $bdd->prepare($sql);
    $req->execute();

    // Historial
    if ($old_adm) {
        $persona = trim(($old_adm['nombre'] ?? '') . ' ' . ($old_adm['apellido'] ?? ''));

        // Lookup cargo names
        $stmt_cargo = $bdd->prepare("SELECT cargo FROM cargos WHERE id=:id");
        $stmt_cargo->execute([':id' => $old_adm['cargo']]);
        $r = $stmt_cargo->fetch();
        $cargo_old_name = $r ? $r['cargo'] : (string)$old_adm['cargo'];

        if ($_POST['cargo_adm'] === 'otro') {
            $cargo_new_name = trim($_POST['cargo_otro_adm'] ?? '');
        } else {
            $stmt_cargo->execute([':id' => $_POST['cargo_adm']]);
            $r = $stmt_cargo->fetch();
            $cargo_new_name = $r ? $r['cargo'] : (string)$_POST['cargo_adm'];
        }

        $campos_adm = [
            'Nombre'   => [$old_adm['nombre'],   $_POST['nombre_adm']],
            'Apellido' => [$old_adm['apellido'], $_POST['apellido_adm']],
            'Teléfono' => [$old_adm['telefono'], $_POST['telefono_adm']],
            'Correo'   => [$old_adm['email'],    $_POST['correo_adm']],
            'Cargo'    => [$cargo_old_name,       $cargo_new_name],
        ];
        foreach ($campos_adm as $nombre_campo => $vals) {
            if ((string)$vals[0] !== (string)$vals[1]) {
                registrar_historial($bdd, $old_adm['id_colegio'], $id_usuario_h, 'Información de contacto',
                    "$nombre_campo contacto ($persona)", $vals[0], $vals[1]);
            }
        }
    }

    header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'&tab=info_contac');

?>
