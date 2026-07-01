<?php
if (isset($_POST["colegio"])) {

    require_once("../php/aut.php");
    include("../conexion/bdd.php");
    require_once("registrar_historial.php");

    $id_usuario_h = intval($_SESSION["id"] ?? 0);

    $cumple = !empty($_POST["cumpleanos_c"]) ? $_POST["cumpleanos_c"] : "0000-00-00";

    $quien_decide_val = ($_POST['quien_decide'] === 'otro')
        ? trim($_POST['quien_decide_otro'] ?? '')
        : $_POST['quien_decide'];

    // Valores previos para el historial
    $req_old = $bdd->prepare("SELECT quien_decide, id_segmento FROM colegios WHERE codigo=:cod");
    $req_old->execute([':cod' => $_POST["cod_colegio"]]);
    $old_colegio = $req_old->fetch();

    $req_cargos_h = $bdd->prepare("SELECT id, cargo FROM cargos");
    $req_cargos_h->execute();
    $cargos_h_map = array_column($req_cargos_h->fetchAll(), 'cargo', 'id');
    $qd_label = function ($val) use ($cargos_h_map) {
        if ($val === '' || $val === null) return '';
        return $cargos_h_map[$val] ?? (string)$val;
    };

    $sql = "UPDATE colegios SET colegio='".$_POST["colegio"]."', departamento='".$_POST["departamento"]."', ciudad='".$_POST["ciudad"]."', direccion='".$_POST["direccion"]."', telefono='".$_POST["telefono_c"]."', web='".$_POST["web"]."', cumpleaños='".$cumple."', responsable='".$_POST["responsable"]."', quien_decide='".$quien_decide_val."', id_segmento='".$_POST["segmento"]."' WHERE codigo='".$_POST["cod_colegio"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();

    if ($old_colegio) {
        if ((string)($old_colegio['quien_decide'] ?? '') !== (string)$quien_decide_val) {
            registrar_historial($bdd, $_POST["id_colegio"], $id_usuario_h, 'Información básica',
                '¿Quién decide?', $qd_label($old_colegio['quien_decide']), $qd_label($quien_decide_val));
        }
        if ((string)($old_colegio['id_segmento'] ?? '') !== (string)$_POST["segmento"]) {
            $seg_map = array_column($bdd->query("SELECT id, segmento FROM segmentos")->fetchAll(), 'segmento', 'id');
            registrar_historial($bdd, $_POST["id_colegio"], $id_usuario_h, 'Información básica',
                'Segmento', $seg_map[$old_colegio['id_segmento']] ?? '', $seg_map[$_POST["segmento"]] ?? '');
        }
    }

    $sql = "SELECT id FROM pension WHERE cod_colegio='".$_POST["cod_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();
    $num = $req->rowCount();

    if ($num < 1) {
        $sql = "INSERT INTO pension (cod_colegio, id_periodo, pension) VALUES('".$_POST["cod_colegio"]."', '".$_POST["periodo"]."', '".$_POST["pension"]."')";
    } else {
        $sql = "UPDATE pension SET pension='".$_POST["pension"]."' WHERE cod_colegio='".$_POST["cod_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    }
    $req = $bdd->prepare($sql);
    $req->execute();

    $req_old_status = $bdd->prepare("SELECT id_status FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_status->execute();
    $old_status = $req_old_status->fetch();

    $sql = "SELECT id FROM colegios_status WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();
    $num = $req->rowCount();

    if ($num < 1) {
        $sql = "INSERT INTO colegios_status (id_colegio, id_periodo, id_status) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["status"]."')";
    } else {
        $sql = "UPDATE colegios_status SET id_status='".$_POST["status"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    }
    $req = $bdd->prepare($sql);
    $req->execute();

    if (!$old_status || (string)$old_status['id_status'] !== (string)$_POST["status"]) {
        $status_map = array_column($bdd->query("SELECT id, status FROM status_cubrimiento")->fetchAll(), 'status', 'id');
        registrar_historial($bdd, $_POST["id_colegio"], $id_usuario_h, 'Información básica',
            'Status', $status_map[$old_status['id_status'] ?? 0] ?? '', $status_map[$_POST["status"]] ?? '');
    }

    $req_old_estcli = $bdd->prepare("SELECT id_estado_cliente FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'");
    $req_old_estcli->execute();
    $old_estcli = $req_old_estcli->fetch();

    $sql = "SELECT id FROM colegios_estados_clientes WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    $req = $bdd->prepare($sql);
    $req->execute();
    $num = $req->rowCount();

    if ($num < 1) {
        $sql = "INSERT INTO colegios_estados_clientes (id_colegio, id_periodo, id_estado_cliente) VALUES('".$_POST["id_colegio"]."', '".$_POST["periodo"]."', '".$_POST["estado_cliente"]."')";
    } else {
        $sql = "UPDATE colegios_estados_clientes SET id_estado_cliente='".$_POST["estado_cliente"]."' WHERE id_colegio='".$_POST["id_colegio"]."' AND id_periodo='".$_POST["periodo"]."'";
    }
    $req = $bdd->prepare($sql);
    $req->execute();

    if (!$old_estcli || (string)$old_estcli['id_estado_cliente'] !== (string)$_POST["estado_cliente"]) {
        $estcli_map = array_column($bdd->query("SELECT id, estado FROM estados_cliente")->fetchAll(), 'estado', 'id');
        registrar_historial($bdd, $_POST["id_colegio"], $id_usuario_h, 'Información básica',
            'Estado a cliente', $estcli_map[$old_estcli['id_estado_cliente'] ?? 0] ?? '', $estcli_map[$_POST["estado_cliente"]] ?? '');
    }

}

header('Location: ../colegio.php?codigo='.$_POST["cod_colegio"].'&periodo='.$_POST["periodo"].'');
?>
