<?php
if (isset($_POST["colegio"])) {

    require_once("../php/aut.php");
    include("../conexion/bdd.php");
    require_once("registrar_historial.php");

    $id_usuario_h = intval($_SESSION["id"] ?? 0);
    $id_colegio_h = intval($_POST["id_colegio"]);

    $cumple = !empty($_POST["cumpleanos_c"]) ? $_POST["cumpleanos_c"] : "0000-00-00";

    // Valores previos del colegio antes del UPDATE
    $req_old_cole = $bdd->prepare("SELECT colegio, departamento, ciudad, direccion, telefono, web, cumpleaños, responsable, propuesta_comercial, quien_decide, id_segmento FROM colegios WHERE codigo='".$_POST["cod_colegio"]."'");
    $req_old_cole->execute();
    $old_cole = $req_old_cole->fetch();

    // Nombres de departamento
    $stmt_dep = $bdd->prepare("SELECT departamento FROM departamentos WHERE id=:id");
    $stmt_dep->execute([':id' => $old_cole['departamento'] ?? '']);
    $dep_row = $stmt_dep->fetch();
    $dep_old_name = $dep_row ? $dep_row['departamento'] : (string)($old_cole['departamento'] ?? '');

    $stmt_dep->execute([':id' => $_POST['departamento']]);
    $dep_row = $stmt_dep->fetch();
    $dep_new_name = $dep_row ? $dep_row['departamento'] : (string)$_POST['departamento'];

    // Nombres de segmento
    $stmt_seg = $bdd->prepare("SELECT segmento FROM segmentos WHERE id=:id");
    $stmt_seg->execute([':id' => $old_cole['id_segmento'] ?? '']);
    $seg_row = $stmt_seg->fetch();
    $seg_old_name = $seg_row ? $seg_row['segmento'] : (string)($old_cole['id_segmento'] ?? '');

    $stmt_seg->execute([':id' => $_POST['segmento']]);
    $seg_row = $stmt_seg->fetch();
    $seg_new_name = $seg_row ? $seg_row['segmento'] : (string)$_POST['segmento'];

    // Etiquetas de "¿Quién decide?"
    $req_cargos_h = $bdd->prepare("SELECT id, cargo FROM cargos");
    $req_cargos_h->execute();
    $cargos_h_map = array_column($req_cargos_h->fetchAll(), 'cargo', 'id');
    $qd_label = function ($val) use ($cargos_h_map) {
        if ($val === '' || $val === null) return '';
        return $cargos_h_map[$val] ?? (string)$val;
    };

    // Valor previo de status
    $req_old_st = $bdd->prepare("SELECT id_status FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_st->execute();
    $old_status_row = $req_old_st->fetch();

    // Valor previo de estado a cliente
    $req_old_ec = $bdd->prepare("SELECT id_estado_cliente FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_ec->execute();
    $old_ec_row = $req_old_ec->fetch();

    $quien_decide_val = ($_POST['quien_decide'] === 'otro')
        ? trim($_POST['quien_decide_otro'] ?? '')
        : $_POST['quien_decide'];

    $propuesta_comercial_val = ($_POST['propuesta_comercial'] ?? 'no') === 'si' ? 'si' : 'no';

    $sql = "UPDATE colegios SET colegio='".$_POST["colegio"]."', departamento='".$_POST["departamento"]."', ciudad='".$_POST["ciudad"]."', direccion='".$_POST["direccion"]."', telefono='".$_POST["telefono_c"]."', web='".$_POST["web"]."', cumpleaños='".$cumple."', responsable='".$_POST["responsable"]."', propuesta_comercial='".$propuesta_comercial_val."', quien_decide='".$quien_decide_val."', id_segmento='".$_POST["segmento"]."' WHERE codigo='".$_POST["cod_colegio"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();

    $sql = "SELECT id FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();

    $num = $req->rowCount();

    if ($num < 1) {

        $sql_s = "INSERT INTO colegios_status (id_colegio, id_periodo, id_status) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["status"]."')";

        $query_s = $bdd->prepare($sql_s);
        if ($query_s == false) {
         print_r($bdd->errorInfo());
         die('Erreur prepare');
        }
        $sth_s = $query_s->execute();
        if ($sth_s == false) {
         print_r($query_s->errorInfo());
         die('Erreur execute');
        }

    } else {

        $sql = "UPDATE colegios_status SET id_status='".$_POST["status"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
        $req = $bdd->prepare($sql);
        $req->execute();

    }

    $sql = "SELECT id FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();

    $num = $req->rowCount();

    if ($num < 1) {

        $sql_s = "INSERT INTO colegios_estados_clientes (id_colegio, id_periodo, id_estado_cliente) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["estado_cliente"]."')";

        $query_s = $bdd->prepare($sql_s);
        if ($query_s == false) {
         print_r($bdd->errorInfo());
         die('Erreur prepare');
        }
        $sth_s = $query_s->execute();
        if ($sth_s == false) {
         print_r($query_s->errorInfo());
         die('Erreur execute');
        }

    } else {

        $sql = "UPDATE colegios_estados_clientes SET id_estado_cliente='".$_POST["estado_cliente"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
        $req = $bdd->prepare($sql);
        $req->execute();

    }

    // --- Historial de cambios ---
    if ($old_cole) {
        $campos_cole = [
            ['nombre' => 'Nombre de la institución', 'old' => trim((string)($old_cole['colegio'] ?? '')),              'new' => trim((string)($_POST['colegio'] ?? ''))],
            ['nombre' => 'Provincia',                 'old' => $dep_old_name,                                          'new' => $dep_new_name],
            ['nombre' => 'Ciudad',                     'old' => trim((string)($old_cole['ciudad'] ?? '')),             'new' => trim((string)($_POST['ciudad'] ?? ''))],
            ['nombre' => 'Ubicación',                  'old' => trim((string)($old_cole['direccion'] ?? '')),          'new' => trim((string)($_POST['direccion'] ?? ''))],
            ['nombre' => 'Teléfono',                   'old' => trim((string)($old_cole['telefono'] ?? '')),           'new' => trim((string)($_POST['telefono_c'] ?? ''))],
            ['nombre' => 'Página web',                 'old' => trim((string)($old_cole['web'] ?? '')),                'new' => trim((string)($_POST['web'] ?? ''))],
            ['nombre' => 'Cumpleaños del colegio',     'old' => trim((string)($old_cole['cumpleaños'] ?? '')),         'new' => trim((string)$cumple)],
            ['nombre' => 'Responsable',                'old' => trim((string)($old_cole['responsable'] ?? '')),       'new' => trim((string)($_POST['responsable'] ?? ''))],
            ['nombre' => 'Propuesta comercial',        'old' => trim((string)($old_cole['propuesta_comercial'] ?? '')),'new' => trim((string)$propuesta_comercial_val)],
            ['nombre' => '¿Quién decide?',             'old' => $qd_label($old_cole['quien_decide'] ?? ''),            'new' => $qd_label($quien_decide_val)],
            ['nombre' => 'Segmento',                   'old' => $seg_old_name,                                        'new' => $seg_new_name],
        ];
        foreach ($campos_cole as $c) {
            if ($c['old'] !== $c['new']) {
                registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', $c['nombre'], $c['old'], $c['new']);
            }
        }
    }

    // Cambio de status
    if ((string)($old_status_row['id_status'] ?? '') !== (string)$_POST['status']) {
        $stmt_st = $bdd->prepare("SELECT status FROM status_cubrimiento WHERE id=:id");
        $stmt_st->execute([':id' => $old_status_row['id_status'] ?? 0]);
        $st_old_row = $stmt_st->fetch();
        $stmt_st->execute([':id' => $_POST['status']]);
        $st_new_row = $stmt_st->fetch();
        registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', 'Status',
            $st_old_row ? $st_old_row['status'] : (string)($old_status_row['id_status'] ?? ''),
            $st_new_row ? $st_new_row['status'] : $_POST['status']);
    }

    // Cambio de estado a cliente
    if ((string)($old_ec_row['id_estado_cliente'] ?? '') !== (string)$_POST['estado_cliente']) {
        $stmt_ec = $bdd->prepare("SELECT estado FROM estados_cliente WHERE id=:id");
        $stmt_ec->execute([':id' => $old_ec_row['id_estado_cliente'] ?? 0]);
        $ec_old_row = $stmt_ec->fetch();
        $stmt_ec->execute([':id' => $_POST['estado_cliente']]);
        $ec_new_row = $stmt_ec->fetch();
        registrar_historial($bdd, $id_colegio_h, $id_usuario_h, 'Información básica', 'Estado a cliente',
            $ec_old_row ? $ec_old_row['estado'] : (string)($old_ec_row['id_estado_cliente'] ?? ''),
            $ec_new_row ? $ec_new_row['estado'] : $_POST['estado_cliente']);
    }

}

header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'');
?>
