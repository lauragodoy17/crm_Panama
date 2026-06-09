<?php
if (isset($_POST["colegio"])) {

    include("../conexion/bdd.php");
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("registrar_historial.php");
    header("Content-Type:text/html;charset=utf-8");

    $id_usuario_h = intval($_SESSION["id"] ?? 0);
    $id_colegio_h = intval($_POST["id_colegio"]);

    // Fetch old colegios values before UPDATE
    $req_old_cole = $bdd->prepare("SELECT colegio, departamento, ciudad, dane, direccion, barrio, telefono, web, correo_i, id_calendario, id_segmento FROM colegios WHERE codigo='".$_POST["cod_colegio"]."'");
    $req_old_cole->execute();
    $old_cole = $req_old_cole->fetch();

    // Lookup department names
    $stmt_dep = $bdd->prepare("SELECT departamento FROM departamentos WHERE id=:id");
    $stmt_dep->execute([':id' => $old_cole['departamento']]);
    $dep_row = $stmt_dep->fetch();
    $dep_old_name = $dep_row ? $dep_row['departamento'] : (string)$old_cole['departamento'];

    $stmt_dep->execute([':id' => $_POST['departamento']]);
    $dep_row = $stmt_dep->fetch();
    $dep_new_name = $dep_row ? $dep_row['departamento'] : (string)$_POST['departamento'];

    // Lookup calendar names
    $stmt_cal = $bdd->prepare("SELECT calendario FROM calendarios WHERE id=:id");
    $stmt_cal->execute([':id' => $old_cole['id_calendario']]);
    $cal_row = $stmt_cal->fetch();
    $cal_old_name = $cal_row ? $cal_row['calendario'] : (string)$old_cole['id_calendario'];

    $stmt_cal->execute([':id' => $_POST['calendario']]);
    $cal_row = $stmt_cal->fetch();
    $cal_new_name = $cal_row ? $cal_row['calendario'] : (string)$_POST['calendario'];

    // Lookup segment names
    $stmt_seg = $bdd->prepare("SELECT segmento FROM segmentos WHERE id=:id");
    $stmt_seg->execute([':id' => $old_cole['id_segmento']]);
    $seg_row = $stmt_seg->fetch();
    $seg_old_name = $seg_row ? $seg_row['segmento'] : (string)$old_cole['id_segmento'];

    $stmt_seg->execute([':id' => $_POST['segmento']]);
    $seg_row = $stmt_seg->fetch();
    $seg_new_name = $seg_row ? $seg_row['segmento'] : (string)$_POST['segmento'];

    // Fetch old status
    $req_old_st = $bdd->prepare("SELECT id_status FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_st->execute();
    $old_status_row = $req_old_st->fetch();

    // Fetch old estado_cliente
    $req_old_ec = $bdd->prepare("SELECT id_estado_cliente FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_ec->execute();
    $old_ec_row = $req_old_ec->fetch();

    $sql = "UPDATE colegios SET colegio='".$_POST["colegio"]."',departamento='".$_POST["departamento"]."',ciudad='".$_POST["ciudad"]."',dane='".$_POST["dane"]."', direccion='".$_POST["direccion"]."', barrio='".$_POST["barrio"]."', telefono='".$_POST["telefono_c"]."', web='".$_POST["web"]."', correo_i='".$_POST["correo_i"]."', id_calendario='".$_POST["calendario"]."', id_segmento='".$_POST["segmento"]."', responsable='".$_POST["responsable"]."' WHERE codigo='".$_POST["cod_colegio"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();

    $sql = "SELECT id FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

    $req = $bdd->prepare($sql);
    $req->execute();

    $num = $req->rowCount();

    if ($num < 1) {

        $sql_s="INSERT INTO colegios_status (id_colegio, id_periodo, id_status) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["status"]."')";

        $query_s = $bdd->prepare( $sql_s );
        if ($query_s == false) {
         print_r($bdd->errorInfo());
         die ('Erreur prepare');
        }
        $sth_s = $query_s->execute();
        if ($sth_s == false) {
         print_r($query_s->errorInfo());
         die ('Erreur execute');
        }

    }else{

        $sql = "UPDATE colegios_status SET id_status='".$_POST["status"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
        $req = $bdd->prepare($sql);
        $req->execute();

    }

    if ($_SESSION["tipo"]!=6) {


        $sql = "SELECT id FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";

        $req = $bdd->prepare($sql);
        $req->execute();

        $num = $req->rowCount();

        if ($num < 1) {

            $sql_s="INSERT INTO colegios_estados_clientes (id_colegio, id_periodo, id_estado_cliente) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["estado_cliente"]."')";

            $query_s = $bdd->prepare( $sql_s );
            if ($query_s == false) {
             print_r($bdd->errorInfo());
             die ('Erreur prepare');
            }
            $sth_s = $query_s->execute();
            if ($sth_s == false) {
             print_r($query_s->errorInfo());
             die ('Erreur execute');
            }

        }else{

            $sql = "UPDATE colegios_estados_clientes SET id_estado_cliente='".$_POST["estado_cliente"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
            $req = $bdd->prepare($sql);
            $req->execute();

        }

    }

    if ($_POST['propuesta_c']==1) {

        $dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos/';
        $nombre_archivo=uniqid()."_".$_FILES['archivo']['name'];
        $fichero_subido = $dir_subida . basename($nombre_archivo);
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
            echo "archivo subido";
        }else{
            $nombre_archivo="";
        }

        $sql_z = "INSERT INTO adjuntos (id_colegio,id_periodo,tipo,adjunto) VALUES ('".$_POST["id_colegio"]."','".$_POST["periodo"]."','1','".$nombre_archivo."')";

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

    }else{

        $sql = "DELETE FROM adjuntos WHERE id_colegio = '".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
        $query = $bdd->prepare( $sql );
        if ($query == false) {
         print_r($bdd->errorInfo());
         die ('Erreur prepare');
        }
        $res = $query->execute();
        if ($res == false) {
         print_r($query->errorInfo());
         die ('Erreur execute');
        }
    }

    // --- Historial de cambios ---
    if ($old_cole) {
        $campos_cole = [
            ['nombre' => 'Nombre del colegio',  'old' => trim((string)($old_cole['colegio'] ?? '')),   'new' => trim((string)($_POST['colegio'] ?? ''))],
            ['nombre' => 'Departamento',         'old' => $dep_old_name,                                'new' => $dep_new_name],
            ['nombre' => 'Ciudad',               'old' => trim((string)($old_cole['ciudad'] ?? '')),    'new' => trim((string)($_POST['ciudad'] ?? ''))],
            ['nombre' => 'DANE',                 'old' => trim((string)($old_cole['dane'] ?? '')),      'new' => trim((string)($_POST['dane'] ?? ''))],
            ['nombre' => 'Dirección',            'old' => trim((string)($old_cole['direccion'] ?? '')), 'new' => trim((string)($_POST['direccion'] ?? ''))],
            ['nombre' => 'Barrio',               'old' => trim((string)($old_cole['barrio'] ?? '')),    'new' => trim((string)($_POST['barrio'] ?? ''))],
            ['nombre' => 'Teléfono',             'old' => trim((string)($old_cole['telefono'] ?? '')),  'new' => trim((string)($_POST['telefono_c'] ?? ''))],
            ['nombre' => 'Sitio web',            'old' => trim((string)($old_cole['web'] ?? '')),       'new' => trim((string)($_POST['web'] ?? ''))],
            ['nombre' => 'Correo institucional', 'old' => trim((string)($old_cole['correo_i'] ?? '')),  'new' => trim((string)($_POST['correo_i'] ?? ''))],
            ['nombre' => 'Calendario',           'old' => $cal_old_name,                                'new' => $cal_new_name],
            ['nombre' => 'Segmento',             'old' => $seg_old_name,                                'new' => $seg_new_name],
        ];
        foreach ($campos_cole as $c) {
            if ($c['old'] !== $c['new']) {
                registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', $c['nombre'], $c['old'], $c['new']);
            }
        }
    }

    // Status change
    if ($old_status_row && (string)$old_status_row['id_status'] !== (string)$_POST['status']) {
        $stmt_st = $bdd->prepare("SELECT status FROM status_cubrimiento WHERE id=:id");
        $stmt_st->execute([':id' => $old_status_row['id_status']]);
        $st_old_row = $stmt_st->fetch();
        $stmt_st->execute([':id' => $_POST['status']]);
        $st_new_row = $stmt_st->fetch();
        registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', 'Estado de la ficha',
            $st_old_row ? $st_old_row['status'] : $old_status_row['id_status'],
            $st_new_row ? $st_new_row['status'] : $_POST['status']);
    }

    // Estado cliente change
    if ($_SESSION["tipo"] != 6 && $old_ec_row && (string)$old_ec_row['id_estado_cliente'] !== (string)$_POST['estado_cliente']) {
        $stmt_ec = $bdd->prepare("SELECT estado FROM estados_cliente WHERE id=:id");
        $stmt_ec->execute([':id' => $old_ec_row['id_estado_cliente']]);
        $ec_old_row = $stmt_ec->fetch();
        $stmt_ec->execute([':id' => $_POST['estado_cliente']]);
        $ec_new_row = $stmt_ec->fetch();
        registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', 'Estado comercial',
            $ec_old_row ? $ec_old_row['estado'] : $old_ec_row['id_estado_cliente'],
            $ec_new_row ? $ec_new_row['estado'] : $_POST['estado_cliente']);
    }

}


header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'');
?>
