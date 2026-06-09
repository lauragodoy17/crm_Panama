<?php
function registrar_historial($bdd, $id_colegio, $id_usuario, $modulo, $campo, $valor_anterior, $valor_nuevo) {
    $sql = "INSERT INTO historial_colegios (id_colegio, id_usuario, modulo, campo, valor_anterior, valor_nuevo, fecha)
            VALUES (:id_colegio, :id_usuario, :modulo, :campo, :valor_anterior, :valor_nuevo, NOW())";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_colegio'     => intval($id_colegio),
        ':id_usuario'     => intval($id_usuario),
        ':modulo'         => $modulo,
        ':campo'          => $campo,
        ':valor_anterior' => (string)$valor_anterior,
        ':valor_nuevo'    => (string)$valor_nuevo,
    ]);
}
